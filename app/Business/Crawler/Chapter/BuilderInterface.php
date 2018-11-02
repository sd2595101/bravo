<?php

namespace App\Business\Crawler\Chapter;

interface BuilderInterface
{
	public function url($bookid);

	public function range();

	public function roules();
}
