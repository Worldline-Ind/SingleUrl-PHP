<?php
for ($iCount = 0; $iCount <= 500; $iCount++) {
    $client = new SoapClient(
        'https://www.tpsl-india.in/PaymentGateway/services/TransactionDetailsNew?wsdl',
        array(
            "trace" => 1,
            "exceptions" => 1
        )
    );
    $response = $client->getTransactionToken(array(
        'msg' => 'fiO5mNtmHothnE3QGTXkUYUSDvPehgAYOCD+ORLrvetFVxTDnW8t5JfoIq2pBnVf0iZ5iO+n8FZ8FxiXKxbHvdyWOf41z8vM/0lJWduThOLsQV3rkDY3CJDv0FDqvPTFYppe2iZo+arT5xfOEKX7WCHz2hBN8e9ObyyAPZ27lQMnGilT9Ooqc+ZHKwIDjTTZlgGv7RaZ5OmwwTeZ7veTIh6SAOgeG4sZqRApDEQ+37q0P4uYzv38FGThET7G2sc2yS3esopV0doAZu/mIg2HNM45mJLZPJv0B7VEOK4GPiTX5HGBNFCJyrnwxnWRtGbRe9vjyCuisleK07a/LKKV/sa5eyWZx2UZt8ExQLf/kv+gVtEj5hnAVxiSwTbmEjc7v+gvofEUjKOshpH80Xt9PcgziPN+rduqcdQ2OqMQl7AeG+nqAo9O2mJcHayOmNOOT6isWMIF46kDuD5d+whH91tMgbwTdKy3qYvHmQawh3nKcVw7r0VjT8Wb3caUyj4uGcJjG9dkDYu+E20h5ZLEBdjyEpbp/OoAB2+4U43T39tJLfbaoN8V9C11o73sikoj4+8sj4BvM+aaiyX0hOdu2BJ7kreN+C9PbzIEdXOKXbFhFTFieZ67816+RqOf7Yp2OMUDHGI/rSZ0WVhrk4jsLw==|T3348~'
    ));
    print_r($response);
    sleep(2);
    echo "\n";
    echo date("Y-m-d H:i:s");
    echo "\n";
}
