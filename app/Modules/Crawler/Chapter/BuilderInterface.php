<?php

namespace App\Modules\Crawler\Chapter;

interface BuilderInterface
{
	public function url($bookid);

	public function range();

	public function roules();
}
