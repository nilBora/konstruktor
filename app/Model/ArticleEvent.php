<?
App::uses('AppModel', 'Model');
class ArticleEvent extends AppModel {

	public $actsAs = array('Ratingable');

	public function addComment($user_id, $message, $article_id, $parent_id) {
		$this->loadModel('ChatMessage');
		$this->loadModel('User');
		$this->loadModel('Article');

		$check = $this->User->findById($user_id);
		if(!$check) {
			return false;
		}

		$check = $this->Article->findById($article_id);
		if(!$check) {
			return false;
		}

		if($parent_id) {
			$check = $this->findById($parent_id);
			if(!$check) {
				return false;
			}
			if($check['ArticleEvent']['article_id'] != $article_id) {
				return false;
			}
		}

		if (!$this->ChatMessage->save($data = compact('message'))) {
			throw new Exception("Message cannot be saved\n".print_r($data, true));
		}

		$msg_id = $this->ChatMessage->id;
		$this->addEvent($article_id, $user_id, $msg_id, $parent_id);

		return true;
	}

	public function editComment($user_id, $message, $id) {
		$this->loadModel('ChatMessage');
		$this->loadModel('User');
		$this->loadModel('Article');

		$event = $this->findById($id);
		if(!$event) {
			return false;
		}

		$check = $this->Article->findById($event['ArticleEvent']['article_id']);
		if(!$check) {
			return false;
		}

		$check = $this->User->findById($user_id);
		if(!$check) {
			return false;
		}

		$chatMessage = $this->ChatMessage->findById($event['ArticleEvent']['msg_id']);
		if(!$chatMessage) {
			return false;
		}

		$id = $chatMessage['ChatMessage']['id'];
		if (!$this->ChatMessage->save($data = compact('id', 'message'))) {
			return false;
		}

		return true;
	}

    /**
     * New function to remove comments from article.
     * This function will find and delete comment and if this comment
     * has child comments it will delete them too
     *
     * @param $user_id
     * @param $id
     * @return bool
     */
	public function removeComment($user_id, $id) {

        $this->loadModel('ChatMessage');
        $this->loadModel('Article');

        $event = $this->findById($id);
        $article = $this->Article->findById($event['ArticleEvent']['article_id']);
        /**
         * @var $articleOwnerId
         * This will allow us to delete comments at owner article
         */
        $articleOwnerId = $article['Article']['owner_id'];

        if($event) {
            if ($articleOwnerId == $user_id || $event['ArticleEvent']['user_id'] == $user_id) {
                /**
                 * @var $allArticleEvents
                 * Find all child comments
                 */
                $conditions = array(
                    'ArticleEvent.parent_id' => $id
                );
                $allArticleEvents = $this->find('all', compact('conditions'));

                if (count($allArticleEvents) ) {
                    foreach ($allArticleEvents as $articleEvent) {

                        $chatMessage = $this->ChatMessage->findById($articleEvent['ArticleEvent']['msg_id']);
                        if(!$chatMessage) {
                            return false;
                        }
                        if (!$this->ChatMessage->delete($chatMessage['ChatMessage']['id'])) {
                            return false;
                        }
                        if (!$this->delete($articleEvent['ArticleEvent']['id'])) {
                            return false;
                        }
                    }
                }
                $chatMessage = $this->ChatMessage->findById($event['ArticleEvent']['msg_id']);
                if(!$chatMessage) {
                    return false;
                }
                if (!$this->ChatMessage->delete($chatMessage['ChatMessage']['id'])) {
                    return false;
                }

                if (!$this->delete($id)) {
                    return false;
                }
                return true;
            }
        }
        return false;
    }

//    public function removeComment($user_id, $id) {
//        $this->loadModel('ChatMessage');
//        $event = $this->findById($id);
//        if($event) {
//            if($event['ArticleEvent']['user_id'] == $user_id) {
//                $chatMessage = $this->ChatMessage->findById($event['ArticleEvent']['msg_id']);
//                if(!$chatMessage) {
//                    return false;
//                }
//                if (!$this->ChatMessage->delete($chatMessage['ChatMessage']['id'])) {
//                    return false;
//                }
//                if (!$this->delete($id)) {
//                    return false;
//                }
//                return true;
//            }
//        }
//        return false;
//    }

	public function addEvent($article_id, $user_id, $msg_id, $parent_id) {
		$data = compact('article_id', 'user_id', 'msg_id', 'parent_id');
		$this->clear();
		if (!$this->save($data)) {
			throw new Exception("Article event cannot be saved\n".print_r($data, true));
		}
	}

	public function timelineEvents($currUserID, $date, $date2, $view = 0) {
		if($view < 2) {
			$this->loadModel('Article');

			//Мои статьи
			$conditions = array(
				'Article.owner_id' => $currUserID,
				'Article.published' => true
			);
			$aArticles = $this->Article->find('all', compact('conditions'));
			$aArticles = Hash::extract($aArticles, '{n}.Article.id');
			//Мои комменты (для ответов на мои комменты)
			$conditions = array(
				'ArticleEvent.user_id' => $currUserID
			);
			$aAnswers = $this->find('all', compact('conditions'));
			$aParents = Hash::extract($aAnswers, '{n}.ArticleEvent.id');

			$order = 'ArticleEvent.created DESC';
			$conditions = $this->dateRange('ArticleEvent.created', $date, $date2);

			if( count($aParents) == 0 ) {
				$conditions['OR'] = array(
					'ArticleEvent.article_id' => $aArticles,
					'ArticleEvent.user_id' => $currUserID,
				);
			} else {
				$conditions['OR'] = array(
					'ArticleEvent.article_id' => $aArticles,
					'ArticleEvent.parent_id' => $aParents,
					'ArticleEvent.user_id' => $currUserID,
				);
			}
			$aEvents = $this->find('all', compact('conditions', 'order'));

			$aArticles = $this->Article->findAllById( Hash::extract($aEvents, '{n}.ArticleEvent.article_id') );
			$aArticles = Hash::combine($aArticles, '{n}.Article.id', '{n}.Article.title');

			foreach($aEvents as &$event) {
				if( $event['ArticleEvent']['parent_id'] ) {
					$event['ArticleEvent']['type'] = 'answer';
				} else {
					$event['ArticleEvent']['type'] = 'comment';
				}
				$event['ArticleEvent']['article_title'] = $aArticles[$event['ArticleEvent']['article_id']];
			}

			return $aEvents;
		}
		return array();
	}

}
