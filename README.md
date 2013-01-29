Password Hash Bundle
====================

Custom password encoder for symfony2, using the new password hash api from php 5.5 (with fallback for 5.3 + 5.4).

Uses the [password_compat implementation](https://github.com/ircmaxell/password_compat) by [Anthony Ferrara](http://blog.ircmaxell.com/) to provide a fallback for PHP 5.3 and 5.4.

In PHP 5.5 it will ignore the fallback and use the native [password_hash functions](http://php.net/manual/en/ref.password.php).

Installation
------------

Use [composer](http://getcomposer.org/) and require the library in your `composer.json`

	{
    	"require": {
        	"christian-riesen/password-hash-bundle": "1.*",
    	}
	}


Update and you have this and the required library all in one package.

Now update the AppKernel.php:

```php

    public function registerBundles()
    {
        $bundles = array(
			// ...
            new ChristianRiesen\PasswordHashBundle\PasswordHashBundle(),
		);
	}
```

Now it's ready to be used in the `security.yml` file in `app\config`

        security:
            encoders:
                Symfony\Component\Security\Core\User\User:
                    id: security.encoder.passwordhash

If you have a different model, you can change it to that, for example, if you followed the [doctrine entity provider cookbook entry](http://symfony.com/doc/master/cookbook/security/entity_provider.html), then you get the following:

        security:
            encoders:
                Acme\UserBundle\Entity\User:
                    id: security.encoder.passwordhash


Configuration
-------------

Comes with one single configuration, the cost factor of bcrypt. Default is set to 15. I chose not to use the built in default value, in order to ensure that some who have less ressources can lower ir, or those who have higher security needs can up it. Even if this value is changed, the system can still read the old passwords without a problem as the cost factor is part of the saved portion.

To alter the default add this to your `config.yml`:

	cr_passwordhash:
		cost: 5

Note: The cost has to be an integer between 4 and 31.

Storage
-------

However you store the password hash you will need always 60 bytes for it. The hash will never be shorter but always exactly this length. Make certain you can store it properly, as it has may contain characters that might cause troubles with hand made queries.

The salt is included in the password hash, so no need for an extra field there.