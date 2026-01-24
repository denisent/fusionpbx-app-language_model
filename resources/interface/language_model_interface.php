<?php

//define the template class
interface language_model_interface {
	//public function set_source_language_model(string $source_language_model);
	//public function set_target_language_model(string $target_language_model);
	//public function get_language_model() : array;
	public function request(string $endpoint, $content);
}
