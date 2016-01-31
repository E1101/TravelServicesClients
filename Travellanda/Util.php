<?php
namespace Tsp\Travellanda;

class Util
{
    /**
     * Parse Response Headers
     *
     * @param string $headers
     *
     * @return array['version'=>string, 'status'=>int, 'reason'=>string, 'headers'=>array(key=>val)]
     */
    static function parseResponseHeaders($headers)
    {
        if (!preg_match_all('/.*[\r\n]?/', $headers, $lines))
            throw new \InvalidArgumentException('Error Parsing Request Message.');

        $lines = $lines[0];

        $firstLine = array_shift($lines);

        $regex   = '/^HTTP\/(?P<version>1\.[01]) (?P<status>\d{3})(?:[ ]+(?P<reason>.*))?$/';
        $matches = [];
        if (!preg_match($regex, $firstLine, $matches))
            throw new \InvalidArgumentException(
                'A valid response status line was not found in the provided string.'
                . ' response:'
                . $headers
            );


        // ...

        $return = [];

        $return['version'] = $matches['version'];
        $return['status']  = (int) $matches['status'];
        $return['reason']  = (isset($matches['reason']) ? $matches['reason'] : '');
        // headers:
        $return['headers'] = [];
        while ($nextLine = array_shift($lines)) {
            if (trim($nextLine) == '')
                // headers end
                break;

            if (! preg_match('/^(?P<label>[^()><@,;:\"\\/\[\]?=}{ \t]+):(?P<value>.*)$/', $nextLine, $matches))
                throw new \InvalidArgumentException(
                    'Valid Header not found: '.$nextLine
                );

            $return['headers'][$matches['label']] = trim($matches['value']);
        }

        return $return;
    }
}
