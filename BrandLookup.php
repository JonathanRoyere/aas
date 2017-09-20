

<?php

  //give access to the wordpress functions
  require_once('../../../wp-load.php');

  $options =  get_option('aas_settings');

	defined('AWS_API_KEY') or define('AWS_API_KEY',  $options["api_key"] );
	defined('AWS_API_SECRET_KEY') or define('AWS_API_SECRET_KEY', $options["aas_secret_key"]);
	defined('AWS_ASSOCIATE_TAG') or define('AWS_ASSOCIATE_TAG', $options["aas_assoc_tag"]);

	require 'lib/AmazonECS.class.php';

	try
	{
	    // get a new object with your API Key and secret key. Lang is optional.
	    // if you leave lang blank it will be US.
	    $amazonEcs = new AmazonECS(AWS_API_KEY, AWS_API_SECRET_KEY, 'com', AWS_ASSOCIATE_TAG);

	    $amazonEcs->associateTag(AWS_ASSOCIATE_TAG);

	    // from now on you want to have pure arrays as response
	    $amazonEcs->returnType(AmazonECS::RETURN_TYPE_ARRAY);

	    // make the search for the item is going to need more specification
      $searchTerms = explode(',', $options['search_terms']);

      $response['Items']['Item'] = array();

      foreach ($searchTerms as $key => $search) {
        // sleep not to have too many requests
        sleep(.7);

        $searchRes = $amazonEcs->country('com')->category('All')->responseGroup('Medium')->search( 'Candy ' . $search);
        $response['Items']['Item'] =  array_merge ( $searchRes['Items']['Item'] , $response['Items']['Item']);
      }

      $response['Items']['Item'] = shuffle_assoc($response['Items']['Item']);

      if(strlen($searchBar) > 2) {
        sleep(.7);
        $searchResponse = $amazonEcs->country('com')->category('All')->responseGroup('Large')->search( 'Candy ' . $searchBar);
        $response['Items']['Item'] =  array_merge ( $searchResponse['Items']['Item'] , $response['Items']['Item']);
      }


    // sort through post data to have specific search
	foreach ($response['Items']['Item'] as $singleItem)
	{

	  $data = array();

    //filter price
    $itemPrice = str_replace('$', '' , $singleItem['ItemAttributes']['ListPrice']['FormattedPrice']);

    if( $minPrice > $itemPrice  || $itemPrice > $maxPrice) {
      continue;
    }

	  $title = $singleItem['ItemAttributes']['Title'];
	  if (mb_strlen($title) > 400)
	  {
	    $title = substr($title,0, 400);
	  }
	  $data['Title'] = $title;
	  $data['url']   = $singleItem['DetailPageURL'];


    if( strlen( $singleItem['MediumImage']['URL'] ) > 1 ) {
      $data['img']  = $singleItem['MediumImage']['URL'];
    }

    elseif( strlen( $singleItem['LargeImage']['URL'] ) > 1 ) {
      $data['img']  = $singleItem['LargeImage']['URL'];
    }
    if( strlen( $singleItem['SmallImage']['URL']) == 0 && strlen( $singleItem['MediumImage']['URL']) == 0 && strlen( $singleItem['LargeImage']['URL']) == 0   ) {

      if($singleItem["ImageSets"]["ImageSet"]["MediumImage"]['URL'] == NULL   ) {
        $data['img']  = $singleItem['SmallImage']['URL'];
      }
      else {
        $data['img'] = $singleItem["ImageSets"]["ImageSet"]["MediumImage"]['URL'];
      }


    }

  if(    $data['img'] == NULL) {
    $data['img'] = $singleItem["ImageSets"]["ImageSet"]["SmallImage"]['URL'];
  }

	  $data['price'] = $singleItem['ItemAttributes']['ListPrice']['FormattedPrice'];
    $data['amount'] = $singleItem['ItemAttributes']['ListPrice']['Amount'];
	  $output[] = $data;
	}

	echo json_encode($output);

	}
	catch(Exception $e)
	{
	  echo $e->getMessage();
	}
