<?php

namespace App\Business\Crawler\Content;

interface BuilderInterface
{
	public function url($bookid, $chapterid);

	public function range();

	public function roules();
}
