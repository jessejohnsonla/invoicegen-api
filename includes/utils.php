<?php


function get_property_safe($class, $property)
{
    if(property_exists($class, $property) && isset($class->$property))
        return $class->$property;
    else
        return '';            
}


?>