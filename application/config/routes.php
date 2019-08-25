<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	http://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/
$route['default_controller'] = 'mailing';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;

$route['login'] = 'mailing/login';
$route['logout'] = 'mailing/logout';
$route['remind-password'] = 'mailing/generatepassword';
$route['generate-password/(:num)/(.+)/(.+)'] = 'mailing/generatenewpassword/$1/$2/$3';
$route['change-password'] = 'mailing/changepassword';
$route['profile'] = 'mailing/changeprofiledata';
$route['about'] = 'mailing/about';
$route['about-rights'] = 'mailing/aboutrights';

$route['users/(.+)/(:num)'] = 'mailing/users/$1/$2';
$route['users'] = 'mailing/users';
$route['adduser'] = 'mailing/adduser';
$route['getimage/(.+)'] = 'mailing/getimage/$1';
$route['options'] = 'mailing/options';
$route['logs/(.+)/(.+)'] = 'mailing/logs/$1/$2';
$route['logs/(.+)'] = 'mailing/logs/$1';
$route['logs'] = 'mailing/logs';

$route['groups'] = 'iogroups/index';
$route['groups/delete-group/(:num)'] = 'iogroups/index/delete/$1';
$route['edit-group/(:num)'] = 'iogroups/edit/$1';
$route['view-group/(:num)'] = 'iogroups/viewgroup/$1';
$route['view-group/(:num)/(:num)'] = 'iogroups/viewgroup/$1/$2';
$route['view-group/(:num)/(:num)/(.+)/(:num)'] = 'iogroups/viewgroup/$1/$2/$3/$4';
$route['add-email/(:num)'] = 'iogroups/addemail/$1';
$route['edit-email/(:num)/(:num)'] = 'iogroups/editemail/$1/$2';
$route['export-group/(:num)'] = 'ioexport/group/$1';
$route['import-group/(:num)'] = 'ioimport/group/$1';
$route['import-to-group/(:num)/(.+)'] = 'ioimport/groupimport/$1/$2';
$route['import-group-wizard'] = 'iogroups/importtogroup';
$route['export-group-wizard'] = 'iogroups/exporttogroup';
$route['exclusion/(:num)/(.+)/(.+)'] = 'iogroups/exclusion/$1/$2/$3';
$route['exclusion/(:num)'] = 'iogroups/exclusion/$1';
$route['exclusion'] = 'iogroups/exclusion';
$route['exclusion-add-email'] = 'iogroups/exclusionadd';

$route['signatures'] = 'iosignatures/index';
$route['signatures/delete-signature/(:num)'] = 'iosignatures/index/delete/$1';
$route['edit-signature/(:num)'] = 'iosignatures/editsignatures/$1';

$route['messages/(.+)/(:num)/(.+)/(:num)'] = 'iomessages/index/$1/$2/$3/$4';
$route['messages/(.+)/(:num)/(.+)'] = 'iomessages/index/$1/$2/$3';
$route['messages/(.+)/(:num)'] = 'iomessages/index/$1/$2';
$route['messages/(.+)'] = 'iomessages/index/$1';
$route['messages'] = 'iomessages/index';
$route['new-message'] = 'iomessages/newmessage';
$route['edit-message/(:num)/(.+)'] = 'iomessages/editmessage/$1/$2';
$route['edit-message/(:num)'] = 'iomessages/editmessage/$1';
$route['stat-message/(:num)/(:num)'] = 'iomessages/statmessage/$1/$2';
$route['stat-message/(:num)'] = 'iomessages/statmessage/$1';
$route['report-message/(:num)'] = 'iomessages/reportmessage/$1';

$route['sends/delete-send/(:num)'] = 'iosends/index/delete/$1';
$route['sends'] = 'iosends/index';
$route['edit-send/(:num)'] = 'iosends/editsends/$1';

$route['timetable/delete-timetable/(:num)'] = 'iotimetable/index/delete/$1';
$route['timetable'] = 'iotimetable/index';
$route['edit-timetable/(:num)'] = 'iotimetable/edittimetable/$1';

$route['sendmanager/add/(:num)'] = 'iosendmanager/addnewmessage/$1';
$route['sendmanager/sender/(.+)/(:num)'] = 'iosendmanager/sender/$1/$2';
$route['sendmanager/sender'] = 'iosendmanager/sender/';

$route['install/step1/setlang/(.+)'] = 'install/step1/setlang/$1';
$route['install/step1'] = 'install/step1/';
$route['install'] = 'install/index/';

?>