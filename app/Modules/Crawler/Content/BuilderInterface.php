<?php

namespace App\Modules\Crawler\Content;

interface BuilderInterface
{
	public function url($bookid, $chapterid);

	public function range();

	public function roules();
}
