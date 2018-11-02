<?php

namespace App\Modules\Crawler\Book;

interface BuilderInterface
{
	public function url($bookid);

	public function range();

	public function roules();
}