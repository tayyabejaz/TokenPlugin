<?php

    /*** Wordpress Database Variables ***/
    global $wpdb;       //Database Global Variables

    /*** WordPress Database Tables ***/
    $post_tables = $wpdb->prefix . "posts";       //Getting the Post Table
    $user_tab = $wpdb->prefix . "users";        //Getting the User Table

    /*** Query a Post Title and Username from Database ***/
    $quer1 = $wpdb->get_results("SELECT $post_tables.ID , $post_tables.post_title , $user_tab.user_nicename FROM $post_tables INNER JOIN $user_tab WHERE $post_tables.post_author = $user_tab.ID");

    $arr = array();
    foreach ($quer1 as $idss)
    {
        $arr[] = $idss->ID;
    }

/*** --- API Calling Section --- ***/
function my_api($postids) {

    /*** Getting Home URL ***/
	$permalink = get_home_url();
	$find_h = '#^http(s)?://#';
	$find_w = '/^www\./';
	$replace = '';
	$output = preg_replace( $find_h, $replace, $permalink );
	$output = preg_replace( $find_w, $replace, $output );
	$options = array(
		'http' =>
			array(
				'ignore_errors' => false,
				'header'        =>
					array(
						0 => 'authorization: Bearer tP6S70k1R3!2Ka!4gWQsjnT3XcpVH6C@t1RXJ0oJ1n#rdyk2TM7!B!6o&R8A$$*u',
					),
			),
	);

	$context  = stream_context_create( $options );
//	echo "Post id is: " . $postids;
//	var_dump($postids);
	$response = file_get_contents(
		"https://public-api.wordpress.com/rest/v1.1/sites/".$output."/stats/post/".$postids,
		false,
		$context
	);

	$response = json_decode( $response );
	return $response->views;
}

    ?>

<style>
    table {
        border-spacing: 1px;
        border-width: thin;
        border-collapse: collapse;
        background: white;
        border-radius: 6px;
        overflow: hidden;
        max-width: 1100px;
        width: 100%;
        margin: 0 auto;
        position: relative;
        align-content: center;
    }
    table * {
        position: relative;
    }
    table td, table th {
        padding-left: 8px;
    }
    table thead tr {
        height: 60px;
        background: #048BA8;
        font-size: 16px;
        align-content: center;
    }
    table tbody tr {
        height: 48px;
        border-bottom: 1px solid #E3F1D5;
    }
    table tbody tr:last-child {
        border: 0;
    }
    table td, table th {
        text-align: center;
    }
    table td.l, table th.l {
        text-align: right;
    }
    table td.c, table th.c {
        text-align: center;
    }
    table td.r, table th.r {
        text-align: center;
    }

    @media screen and (max-width: 35.5em) {
        table {
            display: block;
        }
        table > *, table tr, table td, table th {
            display: block;
        }
        table thead {
            display: none;
        }
        table tbody tr {
            height: auto;
            padding: 8px 0;
        }
        table tbody tr td {
            padding-left: 45%;
            margin-bottom: 12px;
        }
        table tbody tr td:last-child {
            margin-bottom: 0;
        }
        table tbody tr td:before {
            position: absolute;
            font-weight: 700;
            width: 40%;
            left: 10px;
            top: 0;
        }
        table tbody tr td:nth-child(1):before {
            content: "Code";
        }
        table tbody tr td:nth-child(2):before {
            content: "Stock";
        }
        table tbody tr td:nth-child(3):before {
            content: "Cap";
        }
        table tbody tr td:nth-child(4):before {
            content: "Inch";
        }
        table tbody tr td:nth-child(5):before {
            content: "Box Type";
        }
    }
    body {
        background: lightgray;
        font: 400 14px 'Calibri','Arial';
        padding: 20px;
    }

    blockquote {
        color: white;
        text-align: center;
    }

    .button
    {
        border-radius: 5px;
        background: transparent;
        font-weight: bold;
        font-size: medium;
        color: #048BA8;
    }


</style>



<div id="disp"></div>

	<table>
		<thead>
            <th> Post Id     </th>
            <th> Post Title  </th>
            <th> Post Author </th>
            <th> Post Views  </th>
            <th> Tokens      </th>
            <th> Redeem </th>
		</thead>

        <?php
        $i = 0;

        foreach ($quer1 as $dbposts){

            $post_id  = $dbposts->ID;
            $post_title = $dbposts->post_title;
            $user_name = $dbposts->user_nicename;
            $post_views = my_api($arr[$i]);
            $tokens = $post_views * 0.01;
            ?>

              <tr>
                  <td> <?php echo $post_id ?> </td>
                  <td> <?php echo $post_title; ?> </td>
                  <td> <?php echo $user_name; ?> </td>
                  <td> <?php echo $post_views ?></td>
                  <td> <?php echo $tokens ?> </td>
                  <td>
                      <form action="/wp-content/plugins/NewPlugin/assets/transaction.php">
                              <input type="hidden" value="<?php echo $tokens ?>" name="token">
                          <button class="button" type="submit"> Cash Out </button>
                      </form>
                  </td>
              </tr>


        <?php
            $i++;


        }
        ?>

	</table>
