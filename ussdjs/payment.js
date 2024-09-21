// mpesa paymenyt gateway integration using nodejs

const express = require('express');
const router = express.Router();
const request = require('request');
const moment = require('moment');
const base64 = require('base-64');
const config = require('../config/config');
const { response } = require('express');

// mpesa payment gateway
router.post('/mpesa', (req, res) => {
    const { phone, amount, reference } = req.body;
    const timestamp = moment().format('YYYYMMDDHHmmss');
    const password = base64.encode(`${config.mpesa.consumerKey}:${config.mpesa.consumerSecret}`);
    const auth = `Basic ${password}`;
    const url = 'https://sandbox.safaricom.co.ke/oauth/v1/generate?grant_type=client_credentials';
    const token = request.get(url, { headers: { Authorization: auth } }, (error, response, body) => {
        if (error) {
            console.log(error);
        } else {
            const data = JSON.parse(body);
            const access_token = data.access_token;
            const url = 'https://sandbox.safaricom.co.ke/mpesa/stkpush/v1/processrequest';
            const auth = `Bearer ${access_token}`;
            const request = {
                BusinessShortCode: config.mpesa.shortCode,
                Password: password,
                Timestamp: timestamp,
                TransactionType: 'CustomerPayBillOnline',
                Amount: amount,
                PartyA: phone,
                PartyB: config.mpesa.shortCode,
                PhoneNumber: phone,
                CallBackURL: config.mpesa.callbackUrl,
                AccountReference: reference,
                TransactionDesc: 'Payment'
            };
            const options = {
                url,
                headers: {
                    Authorization: auth,
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(request)
            };
            request.post(options, (error, response, body) => {
                if (error) {
                    console.log(error);
                } else {
                    res.json(body);
                }
            });
        }
    });
});
// confirm payment
router.post('/confirm', (req, res) => {
    const { phone, amount, reference } = req.body;
    const timestamp = moment().format('YYYYMMDDHHmmss');
    const password = base64.encode(`${config.mpesa.consumerKey}:${config.mpesa.consumerSecret}`);
    const auth = `Basic ${password}`;
    const url = 'https://sandbox.safaricom.co.ke/oauth/v1/generate?grant_type=client_credentials';
    const token = request.get(url, { headers: { Authorization: auth } }, (error, response, body) => {
        if (error) {
            console.log(error);
        } else {
            const data = JSON.parse(body);
            const access_token = data.access_token;
            const url = 'https://sandbox.safaricom.co.ke/mpesa/stkpushquery/v1/query';
            const auth = `Bearer ${access_token}`;
            const request = {
                BusinessShortCode: config.mpesa.shortCode,
                Password: password,
                Timestamp: timestamp,
                CheckoutRequestID: reference
            };
            const options = {
                url,
                headers: {
                    Authorization: auth,
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(request)
            };
            request.post(options, (error, response, body) => {
                if (error) {
                    console.log(error);
                } else {
                    res.json(body);
                }
            });
        }
    });
});