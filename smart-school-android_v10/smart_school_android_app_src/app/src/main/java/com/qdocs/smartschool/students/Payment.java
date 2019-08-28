package com.qdocs.smartschool.students;

import android.annotation.TargetApi;
import android.app.Activity;
import android.app.ProgressDialog;
import android.content.Intent;
import android.graphics.Bitmap;
import android.os.Bundle;
import android.util.Log;
import android.view.LayoutInflater;
import android.view.View;
import android.webkit.WebResourceError;
import android.webkit.WebResourceRequest;
import android.webkit.WebView;
import android.webkit.WebViewClient;
import android.widget.Toast;

import com.qdocs.smartschool.BaseActivity;
import com.qdocs.smartschool.R;
import com.qdocs.smartschool.utils.Constants;
import com.qdocs.smartschool.utils.Utility;

public class Payment extends BaseActivity {

    WebView webView;
    String feesId, feesTypeId;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        LayoutInflater inflater = (LayoutInflater) this.getSystemService(LAYOUT_INFLATER_SERVICE);
        View contentView = inflater.inflate(R.layout.payment_activity, null, false);
        mDrawerLayout.addView(contentView, 0);

        titleTV.setText(getApplicationContext().getString(R.string.payFees));

        backBtn.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                Intent asd = new Intent(getApplicationContext(), StudentFees.class);
                startActivity(asd);
                finish();
            }
        });

        webView = findViewById(R.id.payment_webview);

        feesId = getIntent().getStringExtra("feesId");
        feesTypeId = getIntent().getStringExtra("feesTypeId");

        String url = Utility.getSharedPreferences(getApplicationContext(), "apiUrl")+Constants.paymentGatewayUrl;

        url += feesId + "/" + feesTypeId + "/" + Utility.getSharedPreferences(getApplicationContext(), "studentId");

        Log.e("URL", url);


        final ProgressDialog pd = ProgressDialog.show(Payment.this, "", "Loading..", true);

        webView.getSettings().setJavaScriptEnabled(true); // enable javascript

        final Activity activity = this;

        webView.setWebViewClient(new WebViewClient() {
            @SuppressWarnings("deprecation")
            @Override
            public void onReceivedError(WebView view, int errorCode, String description, String failingUrl) {
                Toast.makeText(activity, description, Toast.LENGTH_SHORT).show();
            }
            @TargetApi(android.os.Build.VERSION_CODES.M)
            @Override
            public void onReceivedError(WebView view, WebResourceRequest req, WebResourceError rerr) {
                // Redirect to deprecated method, so you can use it in all SDK versions
                onReceivedError(view, rerr.getErrorCode(), rerr.getDescription().toString(), req.getUrl().toString());
            }

            @Override
            public void onPageStarted(WebView view, String url, Bitmap favicon) {
                pd.show();
            }


            @Override
            public void onPageFinished(WebView view, String url) {
                pd.dismiss();
            }

            // Override URL
//            public boolean shouldOverrideUrlLoading(WebView view, String url) {
//                Log.e("OVERRIDE URL ", url);
////                if(url.equalsIgnoreCase("activity_b://test")){
////                    Intent intent=new Intent(getApplicationContext(),StudentFees.class);
////                    startActivity(intent);
////                }
//
//                Log.e("URL","URL "+url);
//                return true;
//            }
        });

        Log.e("Payment URL","URL "+url);
        webView.loadUrl(url);

    }
}
