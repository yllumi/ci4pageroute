<?php

class PageAction {

	// This method handle get request
	public function run(){
		$data['intro'] = '<p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Aliquam voluptate error laboriosam ullam odio quasi repellendus minus provident. Amet dolores repellat doloremque, nemo similique officia molestias quaerat sequi, voluptates rerum.</p>';

		return $data; 
	}


	// This method handle POST request
	public function process(){

	}

}