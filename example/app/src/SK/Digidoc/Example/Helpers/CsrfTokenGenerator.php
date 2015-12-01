<?php
namespace SK\Digidoc\Example\Helpers;

use Kunststube\CSRFP\SignatureGenerator;

/**
 * Class CsrfTokenGenerator
 *
 * @package SK\Digidoc\Example\Helpers
 */
class CsrfTokenGenerator extends SignatureGenerator
{
    /**
     * CsrfTokenGenerator constructor.
     *
     * @param string $secret
     *
     * @throws \InvalidArgumentException
     */
    public function __construct($secret)
    {
        if ($secret === null) {
            throw new \InvalidArgumentException('Secret not set for CsrfTokenGenerator');
        }

        parent::__construct($secret);
    }

    /**
     * Check that token generator seed value is set
     *
     * @return bool
     */
    public function isSecretSet()
    {
        return $this->secret !== null;
    }
}
