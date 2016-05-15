<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
        <title>Booking Detail</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
    </head>
    <body>
        <div style="clear:both">
            <div class="">
                <b>Ticket Detail</b> &nbsp; &nbsp; &nbsp; <b>Date {DATE}</b><b style="float: right;">Ticket No: {BTNO}</b> 
                <hr>
            </div>
            <div class="">
                Booking of {EVENT} ({EVENTYPE})<br>
                Start Date {EVENTSTART}<br>
                End Date {EVENTEND}
            </div>
        </div>
        <div style="clear: both; margin-top: 20px">
            <div class="">
                <b>Booking Detail</b>
                <hr>
            </div>
            <div style="clear: both">
                <table width="300" align="right">
                    <tr>
                        <th style="text-align: left">Number of People</th><td>{CTN}</td>
                    </tr>
                    <tr>
                        <th style="text-align: left">Total</th><td>{TOTAL}</td>
                    </tr>
                </table>
            </div>
        </div>
        <div style="clear: both">
            <hr>
        </div>
    </body>
</html>