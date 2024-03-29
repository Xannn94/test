<?php
namespace TRegx\SafeRegex;

use TRegx\SafeRegex\Constants\PregConstants;
use TRegx\SafeRegex\Exception\CompileSafeRegexException;
use TRegx\SafeRegex\Exception\RuntimeSafeRegexException;
use TRegx\SafeRegex\Exception\SafeRegexException;
use TRegx\SafeRegex\Exception\SuspectedReturnSafeRegexException;
use TRegx\SafeRegex\Guard\GuardedExecution;
use TRegx\SafeRegex\Guard\Strategy\PregFilterSuspectedReturnStrategy;
use TRegx\SafeRegex\Guard\Strategy\PregReplaceSuspectedReturnStrategy;
use TRegx\SafeRegex\Guard\Strategy\SilencedSuspectedReturnStrategy;
use function preg_filter;
use function preg_grep;
use function preg_last_error;
use function preg_match;
use function preg_match_all;
use function preg_quote;
use function preg_replace;
use function preg_replace_callback;
use function preg_replace_callback_array;
use function preg_split;

class preg
{
    /**
     * Perform a regular expression match
     * @link https://php.net/manual/en/function.preg-match.php
     * @param string $pattern <p>
     * The pattern to search for, as a string.
     * </p>
     * @param string $subject <p>
     * The input string.
     * </p>
     * @param string[] $matches [optional] <p>
     * If <i>matches</i> is provided, then it is filled with
     * the results of search. $matches[0] will contain the
     * text that matched the full pattern, $matches[1]
     * will have the text that matched the first captured parenthesized
     * subpattern, and so on.
     * </p>
     * @param int $flags [optional] <p>
     * <i>flags</i> can be the following flag:
     * <b>PREG_OFFSET_CAPTURE</b>
     * If this flag is passed, for every occurring match the appendant string
     * offset will also be returned. Note that this changes the value of
     * <i>matches</i> into an array where every element is an
     * array consisting of the matched string at offset 0
     * and its string offset into <i>subject</i> at offset
     * 1.
     * @param int $offset [optional] <p>
     * Normally, the search starts from the beginning of the subject string.
     * The optional parameter <i>offset</i> can be used to
     * specify the alternate place from which to start the search (in bytes).
     * </p>
     * <p>
     * Using <i>offset</i> is not equivalent to passing
     * substr($subject, $offset) to
     * <b>preg_match</b> in place of the subject string,
     * because <i>pattern</i> can contain assertions such as
     * ^, $ or
     * (?&lt;=x). Compare:
     * <code>
     * $subject = "abcdef";
     * $pattern = '/^def/';
     * preg_match($pattern, $subject, $matches, PREG_OFFSET_CAPTURE, 3);
     * print_r($matches);
     * </code>
     * The above example will output:</p>
     * <pre>
     * Array
     * (
     * )
     * </pre>
     * <p>
     * while this example
     * </p>
     * <code>
     * $subject = "abcdef";
     * $pattern = '/^def/';
     * preg_match($pattern, substr($subject,3), $matches, PREG_OFFSET_CAPTURE);
     * print_r($matches);
     * </code>
     * <p>
     * will produce
     * </p>
     * <pre>
     * Array
     * (
     * [0] => Array
     * (
     * [0] => def
     * [1] => 0
     * )
     * )
     * </pre>
     * </p>
     * @return int|false <b>preg_match</b> returns 1 if the <i>pattern</i>
     * matches given <i>subject</i>, 0 if it does not, or <b>FALSE</b>
     * if an error occurred.
     * @since 4.0
     * @since 5.0
     * @throws SafeRegexException|CompileSafeRegexException|SuspectedReturnSafeRegexException|RuntimeSafeRegexException
     */
    public static function match($pattern, $subject, array &$matches = null, $flags = 0, $offset = 0)
    {
        return GuardedExecution::invoke('preg_match', function () use ($offset, $flags, &$matches, $subject, $pattern) {
            return @preg_match($pattern, $subject, $matches, $flags, $offset);
        });
    }

    /**
     * Perform a global regular expression match
     * @link https://php.net/manual/en/function.preg-match-all.php
     * @param string $pattern <p>
     * The pattern to search for, as a string.
     * </p>
     * @param string $subject <p>
     * The input string.
     * </p>
     * @param string[][] $matches [optional] <p>
     * Array of all matches in multi-dimensional array ordered according to flags.
     * </p>
     * @param int $flags [optional] <p>
     * Can be a combination of the following flags (note that it doesn't make
     * sense to use <b>PREG_PATTERN_ORDER</b> together with
     * <b>PREG_SET_ORDER</b>):
     * <b>PREG_PATTERN_ORDER</b>
     * <p>
     * Orders results so that $matches[0] is an array of full
     * pattern matches, $matches[1] is an array of strings matched by
     * the first parenthesized subpattern, and so on.
     * </p>
     * @param int $offset [optional] <p>
     * Normally, the search starts from the beginning of the subject string.
     * The optional parameter <i>offset</i> can be used to
     * specify the alternate place from which to start the search (in bytes).
     * </p>
     * <p>
     * Using <i>offset</i> is not equivalent to passing
     * substr($subject, $offset) to
     * <b>preg_match_all</b> in place of the subject string,
     * because <i>pattern</i> can contain assertions such as
     * ^, $ or
     * (?&lt;=x). See <b>preg_match</b>
     * for examples.
     * </p>
     * <p>
     * <code>
     * preg_match_all("|]+>(.*)]+>|U",
     * "example: this is a test",
     * $out, PREG_PATTERN_ORDER);
     * echo $out[0][0] . ", " . $out[0][1] . "\n";
     * echo $out[1][0] . ", " . $out[1][1] . "\n";
     * </code>
     * The above example will output:</p>
     * <pre>
     * example: , this is a test
     * example: , this is a test
     * </pre>
     * <p>
     * So, $out[0] contains array of strings that matched full pattern,
     * and $out[1] contains array of strings enclosed by tags.
     * </p>
     * </p>
     * @return int|false the number of full pattern matches (which might be zero),
     * or <b>FALSE</b> if an error occurred.
     * @since 4.0
     * @since 5.0
     * @throws SafeRegexException|CompileSafeRegexException|SuspectedReturnSafeRegexException|RuntimeSafeRegexException
     */
    public static function match_all($pattern, $subject, array &$matches = null, $flags = PREG_PATTERN_ORDER, $offset = 0)
    {
        return GuardedExecution::invoke('preg_match_all', function () use ($offset, $flags, &$matches, $subject, $pattern) {
            return @preg_match_all($pattern, $subject, $matches, $flags, $offset);
        });
    }

    /**
     * Perform a regular expression search and replace
     * @link https://php.net/manual/en/function.preg-replace.php
     * @param string|string[] $pattern <p>
     * The pattern to search for. It can be either a string or an array with
     * strings.
     * </p>
     * <p>
     * Several PCRE modifiers
     * are also available, including the deprecated 'e'
     * (PREG_REPLACE_EVAL), which is specific to this function.
     * </p>
     * @param string|string[] $replacement <p>
     * The string or an array with strings to replace. If this parameter is a
     * string and the <i>pattern</i> parameter is an array,
     * all patterns will be replaced by that string. If both
     * <i>pattern</i> and <i>replacement</i>
     * parameters are arrays, each <i>pattern</i> will be
     * replaced by the <i>replacement</i> counterpart. If
     * there are fewer elements in the <i>replacement</i>
     * array than in the <i>pattern</i> array, any extra
     * <i>pattern</i>s will be replaced by an empty string.
     * </p>
     * <p>
     * <i>replacement</i> may contain references of the form
     * \\n or (since PHP 4.0.4)
     * $n, with the latter form
     * being the preferred one. Every such reference will be replaced by the text
     * captured by the n'th parenthesized pattern.
     * n can be from 0 to 99, and
     * \\0 or $0 refers to the text matched
     * by the whole pattern. Opening parentheses are counted from left to right
     * (starting from 1) to obtain the number of the capturing subpattern.
     * To use backslash in replacement, it must be doubled
     * ("\\\\" PHP string).
     * </p>
     * <p>
     * When working with a replacement pattern where a backreference is
     * immediately followed by another number (i.e.: placing a literal number
     * immediately after a matched pattern), you cannot use the familiar
     * \\1 notation for your backreference.
     * \\11, for example, would confuse
     * <b>preg_replace</b> since it does not know whether you
     * want the \\1 backreference followed by a literal
     * 1, or the \\11 backreference
     * followed by nothing. In this case the solution is to use
     * \${1}1. This creates an isolated
     * $1 backreference, leaving the 1
     * as a literal.
     * </p>
     * <p>
     * When using the deprecated e modifier, this function escapes
     * some characters (namely ', ",
     * \ and NULL) in the strings that replace the
     * backreferences. This is done to ensure that no syntax errors arise
     * from backreference usage with either single or double quotes (e.g.
     * 'strlen(\'$1\')+strlen("$2")'). Make sure you are
     * aware of PHP's string
     * syntax to know exactly how the interpreted string will look.
     * </p>
     * @param string|string[] $subject <p>
     * The string or an array with strings to search and replace.
     * </p>
     * <p>
     * If <i>subject</i> is an array, then the search and
     * replace is performed on every entry of <i>subject</i>,
     * and the return value is an array as well.
     * </p>
     * @param int $limit [optional] <p>
     * The maximum possible replacements for each pattern in each
     * <i>subject</i> string. Defaults to
     * -1 (no limit).
     * </p>
     * @param int $count [optional] <p>
     * If specified, this variable will be filled with the number of
     * replacements done.
     * </p>
     * @return string|string[]|null <b>preg_replace</b> returns an array if the
     * <i>subject</i> parameter is an array, or a string
     * otherwise.
     * </p>
     * <p>
     * If matches are found, the new <i>subject</i> will
     * be returned, otherwise <i>subject</i> will be
     * returned unchanged or <b>NULL</b> if an error occurred.
     * @since 4.0
     * @since 5.0
     * @throws SafeRegexException|CompileSafeRegexException|SuspectedReturnSafeRegexException|RuntimeSafeRegexException
     */
    public static function replace($pattern, $replacement, $subject, $limit = -1, &$count = null)
    {
        return GuardedExecution::invoke('preg_replace', function () use ($limit, $subject, $replacement, $pattern, &$count) {
            return @preg_replace($pattern, $replacement, $subject, $limit, $count);
        }, new PregReplaceSuspectedReturnStrategy($subject));
    }

    /**
     * Perform a regular expression search and replace using a callback
     * @link https://php.net/manual/en/function.preg-replace-callback.php
     * @param string|string[] $pattern <p>
     * The pattern to search for. It can be either a string or an array with
     * strings.
     * </p>
     * @param callable $callback <p>
     * A callback that will be called and passed an array of matched elements
     * in the <i>subject</i> string. The callback should
     * return the replacement string. This is the callback signature:
     * </p>
     * <p>
     * string<b>handler</b>
     * <b>array<i>matches</i></b>
     * </p>
     * <p>
     * You'll often need the <i>callback</i> function
     * for a <b>preg_replace_callback</b> in just one place.
     * In this case you can use an
     * anonymous function to
     * declare the callback within the call to
     * <b>preg_replace_callback</b>. By doing it this way
     * you have all information for the call in one place and do not
     * clutter the function namespace with a callback function's name
     * not used anywhere else.
     * </p>
     * <p>
     * <b>preg_replace_callback</b> and
     * anonymous function
     * <code>
     * /* a unix-style command line filter to convert uppercase
     * * letters at the beginning of paragraphs to lowercase * /
     * $fp = fopen("php://stdin", "r") or die("can't read stdin");
     * while (!feof($fp)) {
     * $line = fgets($fp);
     * $line = preg_replace_callback(
     * '|<p>\s*\w|',
     * function ($matches) {
     * return strtolower($matches[0]);
     * },
     * $line
     * );
     * echo $line;
     * }
     * fclose($fp);
     * </code>
     * </p>
     * @param string|string[] $subject <p>
     * The string or an array with strings to search and replace.
     * </p>
     * @param int $limit [optional] <p>
     * The maximum possible replacements for each pattern in each
     * <i>subject</i> string. Defaults to
     * -1 (no limit).
     * </p>
     * @param int $count [optional] <p>
     * If specified, this variable will be filled with the number of
     * replacements done.
     * </p>
     * @return string|string[]|null <b>preg_replace_callback</b> returns an array if the
     * <i>subject</i> parameter is an array, or a string
     * otherwise. On errors the return value is <b>NULL</b>
     * </p>
     * <p>
     * If matches are found, the new subject will be returned, otherwise
     * <i>subject</i> will be returned unchanged.
     * @since 4.0.5
     * @since 5.0
     * @throws SafeRegexException|CompileSafeRegexException|SuspectedReturnSafeRegexException|RuntimeSafeRegexException
     */
    public static function replace_callback($pattern, callable $callback, $subject, $limit = -1, &$count = null)
    {
        return GuardedExecution::invoke('preg_replace_callback', function () use ($pattern, $limit, $subject, $callback, &$count) {
            return @preg_replace_callback($pattern, $callback, $subject, $limit, $count);
        });
    }

    /**
     * Perform a regular expression search and replace using callbacks
     * @link https://php.net/manual/en/function.preg-replace-callback-array.php
     * @param array|callable[] $patterns_and_callbacks An associative array mapping patterns (keys) to callbacks (values)
     * @param string|string[] $subject
     * @param int $limit [optional]
     * @param int $count [optional]
     * @return string|string[]|null  <p>preg_replace_callback_array() returns an array if the subject parameter is an array, or a string otherwise. On errors the return value is NULL</p>
     * <p>If matches are found, the new subject will be returned, otherwise subject will be returned unchanged.</p>
     * @throws SafeRegexException|CompileSafeRegexException|SuspectedReturnSafeRegexException|RuntimeSafeRegexException
     */
    public static function replace_callback_array($patterns_and_callbacks, $subject, $limit = -1, &$count = null)
    {
        return GuardedExecution::invoke('preg_replace_callback_array', function () use ($patterns_and_callbacks, $subject, $limit, &$count) {
            return @preg_replace_callback_array($patterns_and_callbacks, $subject, $limit, $count);
        });
    }

    /**
     * Perform a regular expression search and replace
     * @link https://php.net/manual/en/function.preg-filter.php
     * @param string|string[] $pattern
     * @param string|string[] $replacement
     * @param string|string[] $subject
     * @param int $limit [optional]
     * @param int $count [optional]
     * @return string|string[]|null an array if the <i>subject</i>
     * parameter is an array, or a string otherwise.
     * </p>
     * <p>
     * If no matches are found or an error occurred, an empty array
     * is returned when <i>subject</i> is an array
     * or <b>NULL</b> otherwise.
     * @since 5.3.0
     * @throws SafeRegexException|CompileSafeRegexException|SuspectedReturnSafeRegexException|RuntimeSafeRegexException
     */
    public static function filter($pattern, $replacement, $subject, $limit = -1, &$count = null)
    {
        return GuardedExecution::invoke('preg_filter', function () use ($pattern, $replacement, $subject, $limit, &$count) {
            return @preg_filter($pattern, $replacement, $subject, $limit, $count);
        }, new PregFilterSuspectedReturnStrategy($subject));
    }

    /**
     * Split string by a regular expression
     * @link https://php.net/manual/en/function.preg-split.php
     * @param string $pattern <p>
     * The pattern to search for, as a string.
     * </p>
     * @param string $subject <p>
     * The input string.
     * </p>
     * @param int $limit [optional] <p>
     * If specified, then only substrings up to <i>limit</i>
     * are returned with the rest of the string being placed in the last
     * substring. A <i>limit</i> of -1, 0 or <b>NULL</b> means "no limit"
     * and, as is standard across PHP, you can use <b>NULL</b> to skip to the
     * <i>flags</i> parameter.
     * </p>
     * @param int $flags [optional] <p>
     * <i>flags</i> can be any combination of the following
     * flags (combined with the | bitwise operator):
     * <b>PREG_SPLIT_NO_EMPTY</b>
     * If this flag is set, only non-empty pieces will be returned by
     * <b>preg_split</b>.
     * @return string[]|array[]|false an array containing substrings of <i>subject</i>
     * split along boundaries matched by <i>pattern</i>, or <b>FALSE</b>
     * if an error occurred.
     * @since 4.0
     * @since 5.0
     * @throws SafeRegexException|CompileSafeRegexException|SuspectedReturnSafeRegexException|RuntimeSafeRegexException
     */
    public static function split($pattern, $subject, $limit = -1, $flags = 0)
    {
        return GuardedExecution::invoke('preg_split', function () use ($pattern, $subject, $limit, $flags) {
            return @preg_split($pattern, $subject, $limit, $flags);
        });
    }

    /**
     * Return array entries that match the pattern
     * @link https://php.net/manual/en/function.preg-grep.php
     * @param string $pattern <p>
     * The pattern to search for, as a string.
     * </p>
     * @param array $input <p>
     * The input array.
     * </p>
     * @param int $flags [optional] <p>
     * If set to <b>PREG_GREP_INVERT</b>, this function returns
     * the elements of the input array that do not match
     * the given <i>pattern</i>.
     * </p>
     * @return array an array indexed using the keys from the
     * <i>input</i> array.
     * @since 4.0
     * @since 5.0
     * @throws SafeRegexException|CompileSafeRegexException|SuspectedReturnSafeRegexException|RuntimeSafeRegexException
     */
    public static function grep($pattern, array $input, $flags = 0): array
    {
        return GuardedExecution::invoke('preg_grep', function () use ($flags, $input, $pattern) {
            return @preg_grep($pattern, $input, $flags);
        }, new SilencedSuspectedReturnStrategy());
    }

    /**
     * Quote regular expression characters
     * @link https://php.net/manual/en/function.preg-quote.php
     * @param string $string <p>
     * The input string.
     * </p>
     * @param string $delimiter [optional] <p>
     * If the optional <i>delimiter</i> is specified, it
     * will also be escaped. This is useful for escaping the delimiter
     * that is required by the PCRE functions. The / is the most commonly
     * used delimiter.
     * </p>
     * @return string the quoted (escaped) string.
     * @since 4.0
     * @since 5.0
     */
    public static function quote($string, $delimiter = null): string
    {
        if (preg_quote('#', null) === '#') {
            return str_replace('#', '\#', preg_quote($string, $delimiter));
        }
        return preg_quote($string, $delimiter);
    }

    /**
     * Returns the error code of the last PCRE regex execution
     * @link https://php.net/manual/en/function.preg-last-error.php
     * @return int one of the following constants (explained on their own page):
     * <b>PREG_NO_ERROR</b>
     * <b>PREG_INTERNAL_ERROR</b>
     * <b>PREG_BACKTRACK_LIMIT_ERROR</b> (see also pcre.backtrack_limit)
     * <b>PREG_RECURSION_LIMIT_ERROR</b> (see also pcre.recursion_limit)
     * <b>PREG_BAD_UTF8_ERROR</b>
     * <b>PREG_BAD_UTF8_OFFSET_ERROR</b> (since PHP 5.3.0)
     * @since 5.2.0
     */
    public static function last_error(): int
    {
        return preg_last_error();
    }

    public static function last_error_constant(): string
    {
        return preg::error_constant(preg_last_error());
    }

    public static function error_constant(int $error): string
    {
        return (new PregConstants())->getConstant($error);
    }
}
