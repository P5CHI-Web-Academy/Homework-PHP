<?=(($_SERVER['argc']%2!=0 && $_SERVER['argc']>=3) ? ( //Number of arguments should be odd and >= 3
    count(array_filter($args=array_slice($_SERVER['argv'], 1),
        function($str, $i) {
            return (!($i%2))&&preg_match("/^[mf]$/i", $str) || ($i%2); //Odd params should 'm' or 'f' case insensitive
        }, ARRAY_FILTER_USE_BOTH)
    ) == $_SERVER['argc']-1 ?
        'Hello '.implode(' and ',array_map(function($arr){return preg_replace(array("/[m]/i","/[f]/i"), array('Mr','Ms'), $arr[0]).' '.ucfirst($arr[1]);},array_chunk($args, 2))).'!' //Join arr elements together
    : 'Unknown title.'
) : 'Missing arguments.')."\n";
