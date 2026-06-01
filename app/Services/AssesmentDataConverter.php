<?php

namespace App\Services;

/**
 * Class AssesmentDataConverter
 *
 * Handles converting percentage value of some assessment data prior storing to database.
 */
class AssesmentDataConverter
{
    /**
     * Converts percentage value of self_esteem from 0-100 to 0-30.
     *
     * @param int $selfEsteem Percentage value of self_esteem.
     * @return int Score value of self_esteem.
     */
    public static function convertSelfEsteem(int $selfEsteem = null) : int
    {
        return (int) round(($selfEsteem / 100) * 30);
    }

    /**
     * Converts percentage value of depression from 0-100 to 0-27.
     *
     * @param int $depression Percentage value of depression.
     * @return int Score value of depression.
     */
    public static function convertDepression(int $depression = null) : int
    {
        return (int) round(($depression / 100) * 27);
    }
}