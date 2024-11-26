<?php

$json = array(

  'orders' => array(
                'name' => 'orders',
                'slug' => 'orders',
                'title' => 'Orders',
                'menu_title' => 'Orders',
                'menu_enabled' => 1,
                'parent' => 0
              ),
  'bookings' => array(
                'name' => 'bookings',
                'slug' => 'bookings',
                'title' => 'Bookings',
                'menu_title' => 'Bookings',
                'menu_enabled' => 1,
                'parent' => 0
              ), 
  'mealdeal' => array(
                'name' => 'meal deal',
                'slug' => 'meal-deal',
                'title' => 'Meal deal',
                'menu_title' => 'Meal deal',
                'menu_enabled' => 1,
                'parent' => 0
              ), 
  'profile' => array(
                'name' => 'profile',
                'slug' => 'profile',
                'title' => 'Profile',
                'menu_title' => 'Profile',
                'menu_enabled' => 1,
                'parent' => 0
              ),
  'settings' => array(
                'name' => 'settings',
                'slug' => 'settings',
                'title' => 'Settings',
                'menu_title' => 'Settings',
                'menu_enabled' => 1,
                'parent' => 0
              ),

);

echo json_encode($json);

?>