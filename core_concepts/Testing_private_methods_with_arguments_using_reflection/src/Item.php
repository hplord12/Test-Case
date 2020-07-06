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

    /**
     * Get a random token with a specified prefix
     *
     * @param string $prefix token prefix
     *
     * @return string The token
     */
    private function getPrefixedToken(string $prefix)
    {
        return uniqid($prefix);
    }
}
