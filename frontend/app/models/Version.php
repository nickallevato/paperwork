<?php

class Version extends PaperworkModel {
	use SoftDeletingTrait;
	protected $softDelete = true;
	protected $table = 'versions';
	protected $fillable = array('previous_id', 'next_id', 'title', 'content', 'content_preview','user_id');

	public function notes() {
		return $this->hasOne('Note');
	}

	public function previous() {
		return $this->belongsTo('Version', 'previous_id');
	}

	public function next() {
		return $this->belongsTo('Version', 'next_id');
	}

	public function attachments() {
		return $this->belongsToMany('Attachment')->withTimestamps();
	}

	public function user() {
		return $this->belongsTo('User');
	}
	
	/**
	 * This could be implemented as an extension. Since Paperwork at the
	 * moment does not support extensions, I am adding as a core feature. 
	 */
	public function getContentAttribute($rawValue) {
		$newValue = "<p>";
		$lineArray = explode("<p>", $rawValue);
		for ($i = 0; $i < count($lineArray); $i++) {
			$pieceArray = explode("<br/>", $lineArray[$i]);
			$pieceArray[0] = "<p>" . $pieceArray[0];
			for ($j = 0; $j < count($pieceArray); $j++) {
				$currentPiece = $pieceArray[$j];
				$newValue .= preg_replace('/\[( |)\](.*)/', '<input type="checkbox" disabled> $2<br/>', $currentPiece);
				$newValue = preg_replace('/\[(X|x)\](.*)/', '<input type="checkbox" checked disabled> $2<br/>', $newValue);
			}
		}
		$newValue = preg_replace('/<br\/>$/', '</p>', $newValue);
		//exit(var_dump($newValue));
		return $newValue;
	}
}

?>
