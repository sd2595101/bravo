<?php

namespace App\Modules\Crawler\Search;

interface BuilderInterface
{
	public function url($keyword);

	public function range();

	public function roules();
}