<?php

require_once('mediaService.php');
// require_once('../config/localConfig.php');
use Httpful\Request;

/**
*  Media service for YouTube. This absracts out YouTube's API calls
*
*/

class youtubeService extends mediaService
{
	/**
	*	@var array An array of regular expressions to extract the YouTube item code
	*/
	var $regex = array(
		'@youtube\.com/embed/([^"\& ]+)@i',
	    '@youtube\.com/v/([^"\& ]+)@i',
	    '@youtube\.com/watch\?v=([^"\& ]+)@i',
	    '@youtube\.com/\?v=([^"\& ]+)@i',
	    '@youtu\.be/([^"\& ]+)@i',
	    '@youtu\.be/v/([^"\& ]+)@i',
	    '@youtu\.be/watch\?v=([^"\& ]+)@i',
	    '@youtu\.be/\?v=([^"\& ]+)@i',
		);

	/**
	*	Checks to see if a video is missing caption information in YouTube
	*	@param string $link_url The URL to the video or video resource
	*	@return bool TRUE if captions are missing, FALSE if captions exists
	*/
	function captionsMissing($link_url)
	{
		$url = 'https://www.googleapis.com/youtube/v3/captions?part=id&videoId=';
		$api_key = constant( 'GOOGLE_API_KEY' );

		if( $youtube_id = $this->isYouTubeVideo($link_url) ) {
			$url = $url.$youtube_id.'&key='.$api_key;
			$response = Request::get($url)->send();

			// If the video was pulled due to copyright violations or is unavailable, the items array will be empty.
			// Another error will result in this case
			if( empty($response->body->items) ) {
				return false;
			}

			$caption_tracks = $response->body->items;

			if( empty($caption_tracks) ) {
				return true;
			}

			foreach ( $caption_tracks as $track ) {
				if ( $track->snippet->trackKind != 'ASR' )
					return false;
			}

			return true;
		}

		return false;

	}

	/**
	*	Checks to see if a video is unavailable on YouTube
	*	@param string $link_url The URL to the video or video resource
	*	@return bool TRUE if a video is unavailable for any reason (Copyright, Deleted, Private, etc.), FALSE if the video exists
	*/
	function videoUnavailable($link_url)
	{
		$url = 'https://www.googleapis.com/youtube/v3/videos?part=contentDetails&id=';
		$api_key = constant( 'GOOGLE_API_KEY' );

		if( $youtube_id = $this->isYouTubeVideo($link_url) ) {
			$url = $url.$youtube_id.'&key='.$api_key;
			$response = Request::get($url)->send();

			return ( empty($response->body->items) )? true: false;
		}

		return false;
  	}

	/**
	*	Checks to see if the provided link URL is a YouTube video. If so, it returns
	*	the video code, if not, it returns null.
	*	@param string $link_url The URL to the video or video resource
	*	@return mixed FALSE if it's not a YouTube video, or a string video ID if it is
	*/
	private function isYouTubeVideo($link_url)
	{
		$matches = null;
		foreach($this->regex as $pattern) {
			if(preg_match($pattern, trim($link_url), $matches)) {
				return $matches[1];
			}
		}
		return false;
	}
}