<?php
/**
 * @package      ITPrism
 * @subpackage   Strings
 * @author       Todor Iliev
 * @copyright    Copyright (C) 2014 Todor Iliev <todor@itprism.com>. All rights reserved.
 * @license      http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */

// no direct access
defined('JPATH_PLATFORM') or die;

/**
 * This class contains methods that are used for handling strings.
 *
 * @package     ITPrism
 * @subpackage  Strings
 */
class ITPrismString
{
    protected $content;

    /**
     * Initialize the object.
     *
     * <code>
     * $content = "If you can dream it, you can do it."
     *
     * $string = new ITPrismString($content);
     * </code>
     */
    public function __construct($content = "")
    {
        $this->content = (string)$content;
    }

    /**
     * The method generates random string.
     * You can set a prefix and specify the length of the string.
     *
     * <code>
     * $hash = new ITPrismString();
     * $hash->generateRandomString(32, "GEN");
     *
     * echo $hash;
     * </code>
     *
     * @param integer $length The length of the string, that will be generated.
     * @param string  $prefix A prefix, which will be added at the beginning of the string.
     *
     * @return string
     */
    public function generateRandomString($length = 10, $prefix = "")
    {
        // Generate string
        $hash = md5(uniqid(time() + mt_rand(), true));
        $hash = substr($hash, 0, $length);

        // Add prefix
        if (!empty($prefix)) {
            $hash = $prefix . $hash;
        }

        $this->content = $hash;

        return $this;
    }

    /**
     * Generate a string of amount based on location.
     * The method uses PHP NumberFormatter ( Internationalization Functions ).
     * If the internationalization library is not loaded, the method generates a simple string ( 100 USD, 500 EUR,... )
     *
     * <code>
     * $options = array(
     *     "intl" => true",
     *     "locale" => "en_GB",
     *     "symbol" => "£",
     *     "position" => 0 // 0 for symbol on the left side, 1 for symbole on the right side.
     * );
     *
     * $amount = new ITPrismString(100);
     * $amount->getAmount(GBP, $options);
     *
     * echo $amount;
     * </code>
     *
     * @param string $currency Currency Code ( GBP, USD, EUR,...)
     * @param array $options Options - "intl", "locale", "symbol",...
     *
     * @return string
     */
    public function getAmount($currency, $options = array())
    {
        $useIntl   = JArrayHelper::getValue($options, "intl", false, "bool");
        $locale    = JArrayHelper::getValue($options, "locale");
        $symbol    = JArrayHelper::getValue($options, "symbol");
        $position  = JArrayHelper::getValue($options, "position");

        // Use PHP Intl library.
        if ($useIntl and extension_loaded('intl')) { // Generate currency string using PHP NumberFormatter ( Internationalization Functions )

            // Get current locale code.
            if (!$locale) {
                $lang   = JFactory::getLanguage();
                $locale = $lang->getName();
            }

            $numberFormat = new NumberFormatter($locale, NumberFormatter::CURRENCY);
            $amount       = $numberFormat->formatCurrency($this->content, $currency);

        } else { // Generate a custom currency string.

            if (!empty($symbol)) { // Symbol

                if (0 == $position) { // Symbol at the beginning.
                    $amount = $symbol . $this->content;
                } else { // Symbol at end.
                    $amount = $this->content . $symbol;
                }

            } else { // Code
                $amount = $this->content . $currency;
            }

        }

        return $amount;
    }

    /**
     * Clean a string from meta tags, spaces and newlines.
     *
     * <code>
     * $content = "If you can <strong>dream</strong> it, you can do it. "
     *
     * $string = new ITPrismString($content);
     * $string->clean();
     *
     * echo $string;
     * </code>
     *
     * @return self
     */
    public function clean()
    {
        $this->content = strip_tags($this->content);
        $this->content = JString::trim(preg_replace('/\r|\n/', ' ', $this->content));

        return $this;
    }

    /**
     * Return part of a string without to break words.
     *
     * <code>
     * $offset  = 0;
     * $length  = 25;
     * $content = "If you can dream it, you can do it."
     *
     * $string = new ITPrismString();
     * $string->substr($offset, $length);
     *
     * echo $string;
     * </code>
     *
     * @param integer $offset
     * @param integer $length
     *
     * @return self
     */
    public function substr($offset, $length)
    {
        $pos           = JString::strpos($this->content, ' ', $length);
        $this->content = JString::substr($this->content, $offset, $pos);

        return $this;
    }

    public function __toString()
    {
        return (string)$this->content;
    }
}
