<?php
class SearchWidget extends WP_Widget {

  function __construct() {
  		// Instantiate the parent object
  		parent::__construct( false, 'AWS Shop Widget' );
  	}

  	function widget( $args, $instance ) { ?>

        <div class="aws-search">
          <form type="post" id="price-search-form" action="" class="aws-inputs">

            <div class="term-search search-cont">
              <label for="search">Item Search</label>
              <input type="text" class="search-info" name="search" placeholder="Item or Brand" value="">
            </div>

            <div class="item-price search-cont">
              <div class="min-price">
                <label for="">Min Price</label>
                <select name="min-price">
                  <option value="1" selected>1.00</option>
                  <option value="5">5.00</option>
                  <option value="10">10.00</option>
                  <option value="20">20.00</option>
                  <option value="25">25.00</option>
                  <option value="30">30.00</option>
                  <option value="40">40.00</option>
                  <option value="50">50.00</option>
                </select>
              </div>

              <div class="max-price">
                <label for="">Max Price</label>
                <select class="" name="max-price">
                  <option value="10">10.00</option>
                  <option value="20">20.00</option>
                  <option value="30">30.00</option>
                  <option value="40">40.00</option>
                  <option selected value="50">50.00</option>
                  <option value="60">60.00</option>
                  <option value="70">70.00</option>
                  <option value="80">80.00</option>
                  <option value="90">90.00</option>
                  <option value="999">100.00+</option>
                </select>
              </div>
            </div>

            <div class="sort-by search-cont">
              <div class="label-head">
                Sort By
              </div>
              <input type="radio" id="relevance" name="sort" value="relevance" checked />
              <label for="relevance" >Relevance</label>

              <input type="radio" id="lower" name="sort" value="lower" />
              <label for="lower" >$ ↓</label>

              <input type="radio" id="higher" name="sort" value="higher" />
              <label for="higher" >$$$ ↑</label>

              <!-- search submit -->
              <div class="submit-cont">
                <input type="submit" name="" value="Search">
              </div>

            </div>

          </form>

        </div>

        <div class="aws-loader">
          <img src="<?php  echo get_site_url(); ?>/wp-content/plugins/aas/assets/loader.gif" alt="loader">
          <h4>Filtering Best Deals.</h4>
        </div>

        <div id="results" class="fluid-container aws-results"></div>

      <script type="text/javascript">
      jQuery(document).ready(function() {


      function aws_search() {
        var resultContainer = jQuery('#results');
        var searchInfo = jQuery("#price-search-form").serializeArray();
        jQuery.ajax(
          {
                 url: '<?php  echo get_site_url(); ?>/wp-content/plugins/aas/ItemLookup.php',
                 data: searchInfo,
                 type: 'post',
                 success: function(response){
                   jQuery('.aws-loader').fadeOut();

                   resultContainer.fadeOut('fast', function() {
                     resultContainer.html('');

                     // parse data to loop through it
                     var result = JSON.parse(response);

                     var html = '<div class="row">';
                     for(var i = 0; i < result.length; i++) {

                        if (!result[i].price) {
                          result[i].price = 'no price found';
                        }

                        if (!result[i].img) {
                          result[i].img = '<?php  echo get_site_url(); ?>/wp-content/plugins/aas/assets/no_image.jpeg';
                        }

                        html += '<div class="res-container center-it shift4">';
                        html += '<div class="product-card">';

                        html += '<div class="product-image">';
                        html += '<img src="'+result[i].img+'">';
                        html += '</div>';

                        html += '<div class="product-info">';
                        html += '<div class="product-title">'+result[i].Title+'</div>';
                        html += '<div class="product-price">'+result[i].price+'</div>';
                         html += '<div class="buy-btn"><a class="btn-0" href="'+result[i].url+'" target="_blank">View Product</a></div>';
                        html += '</div>';

                        html += '</div>';

                        html += '</div>';

                     }
                     html += '</div>'; // end fluid container

                     resultContainer.append(html);
                     resultContainer.fadeIn('fast');
                    });
                  }
        });
      }

      aws_search();

      jQuery('#price-search-form').on('submit',function(e) {
        e.preventDefault();
        jQuery('.aws-loader').fadeIn();
        jQuery('#results').fadeOut();
        aws_search();
      });

      });
      </script>



  	<?php }

  	function update( $new_instance, $old_instance ) {
  		// Save widget options
  	}

  	function form( $instance ) {
  		// Output admin widget options form
  	}
  }

  function myplugin_register_widgets() {
  	register_widget( 'SearchWidget' );
  }

  add_action( 'widgets_init', 'myplugin_register_widgets' );
