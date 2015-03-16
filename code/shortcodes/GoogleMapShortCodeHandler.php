<?php

class GoogleMapShortCodeHandler {

    /* Counter used to ensure unique div ids to allow for multiple maps on on page */
    private static $gsv_ctr = 1;

    public static function parse_googlemap( $arguments, $caption = null, $parser = null ) {
        // each of latitude and longitude are required at a bare minimum
        if(!isset($arguments['latitude'])){
            return '';
        }

        if(!isset($arguments['longitude'])){
            return '';
        }


        // defaults - can be overriden by using zoom and FIXME in the shortcode
        $defaults = array(
            'Zoom' => 5,
            'MapType' => 'ROAD'
        );

        // ensure JavaScript for the map service is only downloaded once
        $arguments['DownloadJS'] = !MapUtil::get_map_already_rendered();
        MapUtil::set_map_already_rendered(true);

        // convert parameters to CamelCase as per standard template conventions
        $arguments['Latitude'] = $arguments['latitude'];
        $arguments['Longitude'] = $arguments['longitude'];

        // optional parameter caption
        if (isset($arguments['caption'])) {
            $arguments['Caption'] = $arguments['caption'];
        }

        // optional parameter pitch
        if (isset($arguments['maptype'])) {
            $arguments['MapType'] = $arguments['maptype'];

            switch ($arguments['MapType']) {
                case 'road':
                    $arguments['MapType'] = 'ROAD';
                    break;
                case 'aerial':
                    $arguments['MapType'] = 'SATELLITE';
                    break;
                case 'hybrid':
                    $arguments['MapType'] = 'HYBRID';
                    break;
                case 'terrain':
                    $arguments['MapType'] = 'TERRAIN';
                    break;
                
                default:
                    $arguments['MapType'] = 'ROAD';
                    break;
            }
        }

        // optional parameter zoom
        if (isset($arguments['zoom'])) {
            $arguments['Zoom'] = $arguments['zoom'];
        }
        
        // the id of the dom element to be used to render the street view
        $arguments['DomID'] = 'google_sc_map_'.self::$gsv_ctr;

        

        // incrememt the counter to ensure a unique id for each map canvas
        self::$gsv_ctr++;

        // merge defaults and arguments
        $customised = array_merge($defaults, $arguments);

        //get streetview template template
        $template = new SSViewer( 'GoogleMapShortCode' );

        //return the template customised with the parmameters
        return $template->process( new ArrayData( $customised ) );
    }
}