<?php

/**
* файл модели ApiArticle
*
* LICENSE: MIT
*
* @category   API
* @package    Api
* @version    Release: 1.0
* @author     Alexander B <answer3ster@gmail.com>
*/

App::uses('AppModel', 'Model');
App::uses('Article', 'Model');
App::uses('Group', 'Model');
App::uses('User', 'Model');
App::uses('Media', 'Media.Model');

/**
* Модель ApiArticle. Обертка под модель Article
*
* @category   API
* @package    Api
* @version    Release: 1.0
* @author     Alexander B <answer3ster@gmail.com> 
*/

class ApiArticle extends AppModel {

	public $useTable = 'articles';
	
	const TEXT = 1;
	const VIDEO = 2;
	
	static $types = array(self::TEXT=>'text',self::VIDEO=>'video');
	
	public $validate = array(
		'search_query' => array(
			'checkNotEmpty' => array(
				'rule' => array('notEmpty'),
				'message' => 'Field is mandatory'
			),
			'queryLen' => array(
				'rule' => array('minLength', 3),
				'message' => 'Minimum Length is 3 characters'
			),
		),
		'group_id' => array(
			'checkNotEmpty' => array(
				'rule' => array('notEmpty'),
				'message' => 'Field is mandatory'
			),
			'numericCheck' => array(
				'rule' => 'numeric',
				'message' => 'Only digits',
				'allowEmpty' => true
				),
		),
		'title' => array(
			'checkNotEmpty' => array(
				'rule' => array('notEmpty'),
				'message' => 'Field is mandatory'
			),
		),
		'published' => array(
			'checkNotEmpty' => array(
				'rule' => array('notEmpty'),
				'message' => 'Field is mandatory'
			),
			'publishedCheck' => array(
				'rule' => array('inList',array(0,1)),
				'message' => 'Incorrect value'
			),
		),
		'type' => array(
			'checkNotEmpty' => array(
				'rule' => array('notEmpty'),
				'message' => 'Field is mandatory'
			),
			'typeCheck' => array(
				'rule' => array('inList',array(self::TEXT,self::VIDEO)),
				'message' => 'Incorrect value'
			),
		),
		'cat_id' => array(
			'checkNotEmpty' => array(
				'rule' => array('notEmpty'),
				'message' => 'Field is mandatory'
			),
			'categoryCheck' => array(
				'rule' => 'inCategoryCheck',
				'message' => 'Incorrect value'
			),
		),
	);
	
	public function inCategoryCheck($check){
		$this->loadModel('ArticleCategory');
		$categories = $this->ArticleCategory->options();
		unset($categories[0]);
		return array_key_exists($check['cat_id'], $categories);
	} 

	protected function _afterInit() {
		$this->loadModel('Article');
	}

	/**
	* Поиск по статьям
	*  
	* @param string $query  
	* @return array
	*/
	public function search($query){

		$data = $this->Article->search($query);
		return $this->formatArticleSearch($data);
	}
	
	private function formatArticleSearch($data){
		$aResult = array();
		foreach ($data as $id=>$article){
			$aResult['Article'][$id]['id'] = $article['Article']['id'];
			$aResult['Article'][$id]['title'] = $article['Article']['title'];
		}
		return $aResult;
	}
	
	/**
	* Статьи пользователя
	*  
	* @param int $userId  
	* @return array
	*/
	public function usersArticles($userId){
		$conditions = array('Article.owner_id'=>$userId,'Article.group_id'=>null);
		$this->Article->bindModel(
				array('belongsTo' => array(
						'ArticleCategory' => array(
							'className' => 'ArticleCategory',
							'foreignKey' => 'cat_id',
							'fields' => 'ArticleCategory.title'
						),
					)
				)				
			);
		$articles = $this->Article->find('all', compact('conditions'));
		if(!$articles){
			return array();
		}
		$this->loadModel('Api.ApiArticleEvent');
		$aID = Hash::extract($articles, '{n}.Article.id');
		$commentsCount = $this->ApiArticleEvent->getCommentsCount($aID);
		$aResult = array();
		foreach ($articles as $id=>$item){
			$aResult['Article'][$id]['id'] = $item['Article']['id'];
			$aResult['Article'][$id]['title'] = $item['Article']['title'];
			$aResult['Article'][$id]['published'] = (int)$item['Article']['published'];
			$aResult['Article'][$id]['created'] = gmdate('Y-m-d\TH:i:s\Z', strtotime($item['Article']['created']));
			$aResult['Article'][$id]['category_id'] = $item['Article']['cat_id'];
			$aResult['Article'][$id]['category_title'] = $item['ArticleCategory']['title'];
			$aResult['Article'][$id]['comments'] = 0;
			if($item['ArticleMedia']['id']){
				$aResult['Article'][$id]['article_img'] = $item['ArticleMedia']['url_img'];
			}
			if(isset($commentsCount[$item['Article']['id']])){
				$aResult['Article'][$id]['comments'] = $commentsCount[$item['Article']['id']];
			}
		}
		return $aResult;
	}
	

	/**
	* Выдает список статей по категории 
	*  
	* @param int $userId
	* @param int $categoryId 
	* @return array
	*/
	public function categoryArticles($userId,$categoryId){
		$this->loadModel('Api.ApiArticleEvent');

		$fields = array('Article.id','Article.title','Article.owner_id','Article.group_id','Article.published','Article.created','ArticleMedia.*','Group.owner_id');
		$conditions = array(
						'Article.cat_id'=>$categoryId,
						'OR'=>array(
							array('Article.published' => 1),
							array('Article.owner_id' => $userId,'published'=>0)
						)
			);
		$order = array('Article.created DESC');
		$limit = 25;
		//администраторы группы
		$joins = array(
		array('table' => 'groups',
			'alias' => 'Group',
			'type' => 'LEFT',
			'conditions' => array(
				'`Group`.id = `Article`.group_id AND `Article`.group_id IS NOT NULL',
				)
			),
		);
		
		$articles =  $this->Article->find('all',compact('conditions', 'fields','joins','order','limit'));
		$articleIds = Hash::extract($articles, '{n}.Article.id');
		
		$this->commentsCount = $this->ApiArticleEvent->getCommentsCount($articleIds);

		$result['Articles'] = Hash::map($articles, '{n}', array($this, 'articlesListCallback'));

		return $result;
		
	}
	
	/**
	* Форматирует список статей для ответа  
	*  
	* @param array $data 
	* @return array
	*/
	function articlesListCallback($data){
		$id = $data['Article']['id'];
		$aResult['id'] = $data['Article']['id'];
		$aResult['title'] = $data['Article']['title'];
		//если статья написана от имени администратора группы, то автор - группа(ссылаясь на слова Тиграна)
		if($data['Article']['group_id'] and $data['Group']['owner_id']==$data['Article']['owner_id']){
			$aResult['author_id'] = $data['Article']['group_id'];
			$aResult['author_entity'] = 'group';
		}else{
			$aResult['author_id'] = $data['Article']['owner_id'];
			$aResult['author_entity'] = 'user';
		}
		$aResult['group_id'] = $data['Article']['group_id'];
		$aResult['published'] = (int)$data['Article']['published'];
		$aResult['created'] = gmdate('Y-m-d\TH:i:s\Z', strtotime($data['Article']['created']));
		$aResult['comments'] = 0;
		if(isset($this->commentsCount[$id])){
			$aResult['comments'] = $this->commentsCount[$id];
		}
		if($data['ArticleMedia']['id']){
			$aResult['article_img'] = $data['ArticleMedia']['url_img'];
		}
		return $aResult;
	}
	
	/**
	* Проверяет доступна ли статья 
	* доступна только опубликованная статья или принадлежащая пользователю
	*  
	* @param int $userId
	* @param int $articleId 
	* @return array
	*/
	public function articleCheckAccess($userId,$articleId){
		$conditions = array(
			'Article.id'=>$articleId,
			'OR'=>array(
				array('Article.published' => 1),
				array('Article.owner_id' => $userId,'published'=>0)
			)
		);
		return $this->Article->find('first',compact('conditions'));
	}
	
	/**
	* Возвращает содержимое статьи 
	*  
	* @param int $articleId 
	* @return array
	*/
	public function getArticleBody($articleId){
		$this->Article->bindModel(
				array('belongsTo' => array(
						'ArticleCategory' => array(
							'className' => 'ArticleCategory',
							'foreignKey' => 'cat_id',
							'fields' => 'ArticleCategory.title'
						),
					)
				)				
		);
		$data = $this->Article->findById($articleId);

		if(!$data){
			return array();
		}
		$result['Article']['id'] = $data['Article']['id'];
		$result['Article']['owner_id'] = $data['Article']['owner_id'];
		$result['Article']['group_id'] = $data['Article']['group_id'];
		$result['Article']['title'] = $data['Article']['title'];
		$result['Article']['published'] = $data['Article']['published'];
		$result['Article']['category_id'] = $data['Article']['cat_id'];
		$result['Article']['category_title'] = $data['ArticleCategory']['title'];
		
		if($data['Article']['type'] == self::$types[self::TEXT]){
			$result['Article']['body'] = $data['Article']['body'];
		}else{
			$result['Article']['video_url'] = $data['Article']['video_url'];
		}
		
		if($data['ArticleMedia']['id']){
			$result['Article']['article_url'] = $data['ArticleMedia']['url_img'];
			$result['Article']['article_file_url'] = $data['ArticleMedia']['url_download'];
		}
		
		if($data['Article']['group_id']){
			$this->loadModel('Group');
			$this->Group->unbindModel(array('hasMany'=>array('GroupAchievement')));
			$author = $this->Group->findById($data['Article']['group_id'],array('Group.title','GroupMedia.*'));
			$result['Article']['author_name'] = $author['Group']['title'];
			$result['Article']['author_img'] = $author['GroupMedia']['url_img'];
		}else{
			$this->loadModel('User');
			$this->User->unbindModel(array('hasMany'=>array('UserAchievement')));
			$author = $this->User->findById($data['Article']['owner_id'],array('User.full_name','UserMedia.*'));
			$result['Article']['author_name'] = $author['User']['full_name'];
			$result['Article']['author_img'] = $author['UserMedia']['url_img'];
		}
		$result['Article']['created'] = gmdate('Y-m-d\TH:i:s\Z', strtotime($data['Article']['created']));
		
		return $result;
	}
	
	public function saveArticle($data){
		if(!$this->Article->save($data)){
			throw new Exception("Can't save Article");
		}
		return $this->Article->id;
	}
	
	public function removeArticle($id){
		$this->Article->delete($id);
		$this->loadModel('Media.Media');
		$media = $this->Media->findByObjectIdAndObjectType($id,'Article');
		if(isset($media['Media']['id'])){
			$this->Media->delete($media['Media']['id']);
		}
	}
}
?>
