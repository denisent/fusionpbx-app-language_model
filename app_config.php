<?php

	//application details
		$apps[$x]['name'] = 'Language Model';
		$apps[$x]['uuid'] = 'a354fd53-2217-4e2e-b922-2daba2fc167a';
		$apps[$x]['category'] = 'AI';
		$apps[$x]['subcategory'] = '';
		$apps[$x]['version'] = '1.0';
		$apps[$x]['license'] = '';
		$apps[$x]['url'] = 'http://www.fusionpbx.com';
		$apps[$x]['description']['en-us'] = 'Language model class interface.';

	//default settings
		$y=0;
		$apps[$x]['default_settings'][$y]['default_setting_uuid'] = "b1f3d511-5f42-4814-8eaf-4e99d2765b22";
		$apps[$x]['default_settings'][$y]['default_setting_category'] = "language_model";
		$apps[$x]['default_settings'][$y]['default_setting_subcategory'] = "enabled";
		$apps[$x]['default_settings'][$y]['default_setting_name'] = "boolean";
		$apps[$x]['default_settings'][$y]['default_setting_value'] = "true";
		$apps[$x]['default_settings'][$y]['default_setting_enabled'] = "false";
		$apps[$x]['default_settings'][$y]['default_setting_description'] = "Language Model API enabled.";
		$y++;
		$apps[$x]['default_settings'][$y]['default_setting_uuid'] = "e24df779-8ff5-4a42-b219-e56866bb1f8c";
		$apps[$x]['default_settings'][$y]['default_setting_category'] = "language_model";
		$apps[$x]['default_settings'][$y]['default_setting_subcategory'] = "engine";
		$apps[$x]['default_settings'][$y]['default_setting_name'] = "text";
		$apps[$x]['default_settings'][$y]['default_setting_value'] = "ollama";
		$apps[$x]['default_settings'][$y]['default_setting_enabled'] = "false";
		$apps[$x]['default_settings'][$y]['default_setting_description'] = "Language Model engine. Options: ollama, openai";
		$y++;
		$apps[$x]['default_settings'][$y]['default_setting_uuid'] = "c212e66f-b799-4014-8964-5bc88b2cda21";
		$apps[$x]['default_settings'][$y]['default_setting_category'] = "language_model";
		$apps[$x]['default_settings'][$y]['default_setting_subcategory'] = "api_key";
		$apps[$x]['default_settings'][$y]['default_setting_name'] = "text";
		$apps[$x]['default_settings'][$y]['default_setting_value'] = "";
		$apps[$x]['default_settings'][$y]['default_setting_enabled'] = "false";
		$apps[$x]['default_settings'][$y]['default_setting_description'] = "Language Model API Key";
		$y++;
		$apps[$x]['default_settings'][$y]['default_setting_uuid'] = "3d527430-2394-45d2-a629-a7657086446b";
		$apps[$x]['default_settings'][$y]['default_setting_category'] = "language_model";
		$apps[$x]['default_settings'][$y]['default_setting_subcategory'] = "api_url";
		$apps[$x]['default_settings'][$y]['default_setting_name'] = "text";
		$apps[$x]['default_settings'][$y]['default_setting_value'] = "";
		$apps[$x]['default_settings'][$y]['default_setting_enabled'] = "false";
		$apps[$x]['default_settings'][$y]['default_setting_description'] = "Language Model API URL";
		$y++;

