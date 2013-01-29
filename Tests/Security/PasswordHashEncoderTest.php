<?php
namespace ChristianRiesen\PasswordHashBundle\Tests\Security;

use ChristianRiesen\PasswordHashBundle\Security\PasswordHashEncoder;

class PasswordHashEncoderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @expectedException \InvalidArgumentException
     */
    public function testConstructCostTooLow()
    {
        $encoder = new PasswordHashEncoder(3);
    }
    
    /**
     * @expectedException \InvalidArgumentException
     */
    public function testConstructCostTooHigh()
    {
        $encoder = new PasswordHashEncoder(32);
    }
    
    /**
     * @expectedException \InvalidArgumentException
     */
    public function testConstructCostNotNumber()
    {
        $encoder = new PasswordHashEncoder('a');
    }
    
    /**
     * @expectedException \InvalidArgumentException
     */
    public function testConstructCostNull()
    {
        $encoder = new PasswordHashEncoder(null);
    }
    
    /**
     * Checking if the default value creates no error
     */
    public function testConstructCostDefault()
    {
        $encoder = new PasswordHashEncoder();
        $this->assertInstanceOf('ChristianRiesen\PasswordHashBundle\Security\PasswordHashEncoder', $encoder);
    }

    /**
     * Going trhough the possible values and seeing if one of them causes an error
     */
    public function testConstructCost()
    {
        for ($cost = 4; $cost <= 31; $cost++) {
            $encoder = new PasswordHashEncoder($cost);
            $this->assertInstanceOf('ChristianRiesen\PasswordHashBundle\Security\PasswordHashEncoder', $encoder);
        }
    }

    /**
     * Tests the general workflow you expect from the encoder
     */
    public function testWorkflow()
    {
        $password = 'Password.1';
        
        // Checking the workflow for the lower costs, higher would run too long
        for ($cost = 4; $cost <= 8; $cost++) {
            $encoder = new PasswordHashEncoder($cost);
            $hash = $encoder->encodePassword($password);
        
            $this->assertEquals(60, strlen($hash), 'Hash has to be 60 characters long');
            
            // Check if the password validates and validates wrong against the wrong password
            $this->assertTrue($encoder->isPasswordValid($hash, $password));
            $this->assertFalse($encoder->isPasswordValid($hash, "Wrong Password"));
        }
    }
}