<?php
namespace ChristianRiesen\PasswordHashBundle\Security;

use Symfony\Component\Security\Core\Encoder\PasswordEncoderInterface;

/**
 * Implements the PHP >= 5.5.0 password hash functions as an encoder
 *
 * @license MIT
 * @link http://php.net/manual/en/function.password-hash.php
 * @author Christian Riesen <chris.riesen@gmail.com>
 */
class PasswordHashEncoder implements PasswordEncoderInterface
{
    /**
     * Bcrypt cost parameter
     * Can be between 4 and 31 only.
     *
     * @var integer
     */
    protected $cost;

    /**
     * @param  integer                   $cost
     * @throws \InvalidArgumentException
     */
    public function __construct($cost = 15)
    {
        $cost = (int) $cost;

        if ($cost < 4 || $cost > 31) {
            throw new \InvalidArgumentException(sprintf("password_hash(): Invalid bcrypt cost parameter specified: %d", $cost));
        }

        $this->cost = sprintf("%02d", $cost);
    }

    /* (non-PHPdoc)
     * @see \Symfony\Component\Security\Core\Encoder\PasswordEncoderInterface::encodePassword()
     */
    public function encodePassword($raw, $salt = null)
    {
        // Generating our own salt
        // To clarify: With this method the salt will be stored inside the
        // result of the function password_hash, so no extra saving of salt is
        // needed!
        $salt = $this->generateSalt();
        $options = array('cost' => $this->cost, 'salt' => $salt);

        return password_hash($raw, PASSWORD_BCRYPT, $options);
    }

    /* (non-PHPdoc)
     * @see \Symfony\Component\Security\Core\Encoder\PasswordEncoderInterface::isPasswordValid()
     */
    public function isPasswordValid($encoded, $raw, $salt = null)
    {
        echo 'bla'; exit();
        //var_dump($raw, $encoded); exit();
        return password_verify($raw, $encoded);
    }

    /**
     * Creates a salt, with printable ascii chars
     *
     * @return string
     */
    protected function generateSalt()
    {
        $salt = '';

        // bcrypt expects a 22 char salt. Less and it's not happy, more will be
        // truncated and wasted
        for ($i = 0; $i < 22; $i++) {
            $salt .= chr(rand(33, 125));
        }

        return $salt;
    }
}
