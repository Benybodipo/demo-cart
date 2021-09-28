<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    <div class="container" style="background: #f9f9f9; max-width: 700px; margin: 0 auto; padding: 25px;">
        <H4>Welcome {{$data['user']}}</H4>
        <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Repudiandae nemo tempore aliquam veritatis laboriosam repellat rerum nam quibusdam? Ducimus delectus at minima animi.</p>
        <br>
        <br>
        <br>
        <i><small>Add this 3 field in your .env</small></i>
        <table style="border-collapse: collapse; border: 1px solid lightgray; margin-bottom: 25px">
            <tr>
                <td style="border: 1px solid lightgray; padding: 10px;">DEMO_API_ID=</td>
                <td style="border: 1px solid lightgray; padding: 10px;">{{$data['id']}}</td>
            </tr>
            <tr>
                <td style="border: 1px solid lightgray; padding: 10px;">DEMO_API_KEY=</td>
                <td style="border: 1px solid lightgray; padding: 10px;">{{$data['key']}}</td>
            </tr>
            <tr>
                <td style="border: 1px solid lightgray; padding: 10px;">DEMO_API_USER=</td>
                <td style="border: 1px solid lightgray; padding: 10px;">{{$data['user']}}</td>
            </tr>
        </table>

        <p style="color: red;"> * Please use your Api Key to access </p>
        <a href="{{route('access-cart')}}" style="color: white; padding: 15px 30px; background: green; text-decoration: none; margin-top: 50px; display:inline-block;">Access Your Card</a>
    </div>
</body>
</html>