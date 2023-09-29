<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Mukta:wght@400;500;600;700;800&display=swap" rel="stylesheet">
</head>

<body style="font-family: 'Mukta', sans-serif;">
    <div id="template">
        <table style="width: 620px; margin: 0 auto;">
            <tr>
                <td>
                    <img src="/header.png" alt="">
                </td>
            </tr>
            <tr>
                <td>
                    <p style="font-family: 'Poppins', sans-serif; line-height: 30px;">
                        Dear @{{user.first_name}} @{{user.last_name}} @{{user.middle_name}}, We are
                        contacting you because there is an <i>amount due</i> on your purchase @{{purchase.id}}
                    </p>
                </td>
            </tr>
            <tr>
                <td style="padding: 30px;">
                    <table style="width: 100%; border: none; border-collapse: collapse;">
                        <thead>
                            <tr>
                                <th colspan="3"
                                    style="text-align: center; background-color: #352f4b; color: white; padding: 15px; border-radius: 10px 10px 0px 0px; font-size: large; letter-spacing: 1px;">
                                    Purchase Summary</th>
                            </tr>
                            <tr>
                                <td style="text-align: left; padding: 10px 5px;">Date:</td>
                                <td colspan="2" style="text-align: left;">@{{format created_at}}</td>
                            </tr>
                        </thead>
                        <tbody id="productsCompiled"></tbody>
                        <tfoot>
                            <tr>
                                <td></td>
                                <td style="padding: 10px 0px; font-weight: bolder;">Total amount</td>
                                <td style="text-align: right; padding-right: 5px; white-space: nowrap;">@{{amount}} <b>Kn</b></td>
                            </tr>
                            <tr
                                style="background-color: #ededed; border: 1px solid #cdcdcd; border-left: none; border-right: none;">
                                <td></td>
                                <td style="padding: 10px 0px; font-weight: bolder;">Amount paid</td>
                                <td style="text-align: right; padding-right: 5px;">@{{amount}} <b>Kn</b></td>
                            </tr>
                        </tfoot>
                    </table>
                </td>
            </tr>
            <tr>
                <td>
                    <p>
                        According to our records, the <i>amount due</i> is @{{dueAmount amount amount}} Kn. Please, click on the next
                        button
                        <b>to pay</b> this difference:
                    </p>
                </td>
            </tr>
            <tr>
                <td style="text-align: center; padding: 30px;">
                    <a href="https://pet-shop.buckhill.com.hr/checkout"
                        style="background-color: #352f4b; padding: 10px 50px; border-radius: 50px; text-decoration: none; color: white; text-transform: uppercase;">Pay
                        Now</a>
                </td>
            </tr>
            <tr>
                <td>
                    <p>
                        If you have any other concerns, please contact our technical support team. 
                    </p>
                    <p>Kind regards,</p> 
                    <p> Petson Team </p>
                </td>
            </tr>
        </table>

    </div>

    <script id="products" type="text/x-handlebars-template">
        @{{#each products}}
        <tr style="background-color: #ededed; border: 1px solid #cdcdcd; border-left: none; border-right: none;">
            <td style="padding: 10px 5px;">@{{this.quantity}}x</td>
            <td>@{{this.product}}</td>
            <td style="text-align: right; padding-right: 5px; white-space: nowrap;">@{{this.price}} Kn</td>
        </tr>
        @{{/each}}
    </script>

    <!-- Include Handlebars from a CDN -->
    <script src="https://cdn.jsdelivr.net/npm/handlebars@latest/dist/handlebars.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment.min.js"></script>
    <script>
        Handlebars.registerHelper("dueAmount", function (total, paid) {
            return total - paid;
        });
        Handlebars.registerHelper("format", function (datetime) {
            return moment(datetime).format('MMMM Do YYYY, h:mm:ss a');;
        });

        var data = {!!json_encode($order)!!};

        var products = Handlebars.compile(document.getElementById("products").innerHTML);

        document.getElementById("productsCompiled").innerHTML = products(data);

        var template = Handlebars.compile(document.getElementById("template").innerHTML);

        document.getElementById("template").innerHTML = template(data);
    </script>
</body>

</html>