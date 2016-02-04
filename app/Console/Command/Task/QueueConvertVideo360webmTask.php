<?php
/**
 *
 */
App::uses('QueueTask', 'Queue.Console/Command/Task');

/**
 * ConvertVideo Task.
 *
 */
class QueueConvertVideo360webmTask extends QueueTask {

	/**
	 * @var QueuedTask
	 */
	public $QueuedTask;

	/**
	 * Timeout for run, after which the Task is reassigned to a new worker.
	 *
	 * @var int
	 */
	public $timeout = 300;

	/**
	 * Number of times a failed instance of this task should be restarted before giving up.
	 *
	 * @var int
	 */
	public $retries = 3;

	/**
	 * Stores any failure messages triggered during run()
	 *
	 * @var string
	 */
	public $failureMessage = '';

	/**
	 * ConvertVideo run function.
	 * This function is executed, when a worker is executing a task.
	 * The return parameter will determine, if the task will be marked completed, or be requeued.
	 *
	 * @param array $data The array passed to QueuedTask->createJob()
	 * @param int $id The id of the QueuedTask
	 * @return bool Success
	 */
	public function run($data, $id = null) {

		/**	@var \FFMpeg\FFMpeg $ffmpeg */
		$ffmpeg = \FFMpeg\FFMpeg::create(array(
			'ffmpeg.binaries'  => Configure::read('ffmpeg_path'),
			'ffprobe.binaries' => Configure::read('ffprobe_path'),
			'timeout'          => 0, // The timeout for the underlying process
			'ffmpeg.threads'   => 12,   // The number of threads that FFMpeg should use
		));
		//For debug
		$ffmpeg->getFFMpegDriver()->listen(new \Alchemy\BinaryDriver\Listeners\DebugListener());
		$ffmpeg->getFFMpegDriver()->on('debug', function ($message) {
			echo $message."\n";
		});

		$filepath = $data['filepath'];
		$filename = $data['filename'];
		$ext = $data['ext'];
		$width = $data['width'];
		$height = $data['height'];

		$video = $ffmpeg->open($filepath.$filename.$ext);
		$video
			->filters()
			->resize(new FFMpeg\Coordinate\Dimension($width, $height), \FFMpeg\Filters\Video\ResizeFilter::RESIZEMODE_INSET)
			->synchronize();

		$webm = new FFMpeg\Format\Video\WebM();
		$webm->setKiloBitrate(800);

		$video->save($webm, $filepath.$filename.'_360p.webm');

		$this->loadModel('Media');
		$file = $this->Media->findById($data['media_id']);
		$converted = $file['Media']['converted'] + 1;
		$this->Media->updateAll(array('converted' => $converted), array('id' => $file['Media']['id']));

		return true;
	}
}
