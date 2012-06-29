<?php
/**
 * Generates a username using a single value by incrementing an appended number until a none used value is found
 * @param string $username_base
 */
function wtgcsv_generateusername_singlevalue($username_base)
{
    $attempt = 0;
    $limit = 500;// maximum trys - would we ever get so many of the same username with appended number incremented?
    $exists = true;// we need to change this to false before we can return a value
    
    // clean the string
    $username_base = preg_replace('/([^@]*).*/', '$1', $username_base );
    
    // ensure giving string does not already exist as a username else we can just use it
    $exists = username_exists( $username_base );
    if( $exists == false )
    {
        return $username_base;
    }
    else
    {
        // if $suitable is true then the username already exists, increment it until we find a suitable one
        while( $exists != false )
        {
            ++$attempt;
            $username = $username_base.$attempt;
            
            // username_exists returns id of existing user so we want a false return before continuing
            $exists = username_exists( $username );
            
            // break look when hit limit or found suitable username
            if($attempt > $limit || $exists == false )
            {
                break;
            }
        }
        
        // we should have our login/username by now
        if ( $exists == false ) 
        {
            return $username;
        }    
    }
}  
?>