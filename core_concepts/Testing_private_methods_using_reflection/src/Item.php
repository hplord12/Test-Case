<?php

/**
 * Item
 *
 * An example item class
 */
class Item
{

    /**
     * Get a random token
     *
     * @return string The token
     */
    private function getToken()
    {
        return uniqid();
    }
}
