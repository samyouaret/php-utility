<?php

namespace PHPUtility\System\Utilities;

class Memory
{
    public static function peakUsage()
    {
        return memory_get_peak_usage();
    }

    public static function realPeakUsage()
    {
        return memory_get_peak_usage(true);
    }

    public static function usage()
    {
        return memory_get_usage();
    }

    public static function realUsage()
    {
        return memory_get_usage(true);
    }
}
