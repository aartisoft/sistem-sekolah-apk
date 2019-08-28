package com.qdocs.smartschool.students;

import android.app.ProgressDialog;
import android.graphics.Color;
import android.os.Bundle;
import android.support.v7.widget.DefaultItemAnimator;
import android.support.v7.widget.LinearLayoutManager;
import android.support.v7.widget.RecyclerView;
import android.util.Log;
import android.view.LayoutInflater;
import android.view.View;
import android.widget.TextView;
import android.widget.Toast;

import com.android.volley.AuthFailureError;
import com.android.volley.Request;
import com.android.volley.RequestQueue;
import com.android.volley.Response;
import com.android.volley.VolleyError;
import com.android.volley.VolleyLog;
import com.android.volley.toolbox.StringRequest;
import com.android.volley.toolbox.Volley;
import com.qdocs.smartschool.BaseActivity;
import com.qdocs.smartschool.R;
import com.qdocs.smartschool.adapters.StudentReportCard_ExamResultAdapter;
import com.qdocs.smartschool.utils.Constants;
import com.qdocs.smartschool.utils.Utility;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import java.io.UnsupportedEncodingException;
import java.util.ArrayList;
import java.util.HashMap;
import java.util.Hashtable;
import java.util.Map;

public class StudentReportCard_ExamResult extends BaseActivity {

    RecyclerView examResultview;
    TextView examNameTV, totalTV, percentageTV, gradeTV, gradeHeaderTV, statusTV;
    ArrayList<String> examSubjectList = new ArrayList<String>();
    ArrayList<String> examPassingMarksList = new ArrayList<String>();
    ArrayList<String> examObtainedMarksList = new ArrayList<String>();
    ArrayList<String> examResultList = new ArrayList<String>();
    StudentReportCard_ExamResultAdapter adapter;

    public Map<String, String> params = new Hashtable<String, String>();
    public Map<String, String>  headers = new HashMap<String, String>();

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        LayoutInflater inflater = (LayoutInflater) this.getSystemService(LAYOUT_INFLATER_SERVICE);
        View contentView = inflater.inflate(R.layout.student_report_card_exam_result_activity, null, false);
        mDrawerLayout.addView(contentView, 0);

        titleTV.setText(getApplicationContext().getString(R.string.reportCard));

        examResultview = (RecyclerView) findViewById(R.id.studentReportCard_examResultListview);

        examNameTV = findViewById(R.id.studentReportCard_examResultNameTV);
        statusTV = findViewById(R.id.studentReportCard_statusTV);
        totalTV = findViewById(R.id.studentReportCard_examResulTotalTV);
        percentageTV = findViewById(R.id.studentReportCard_percentageTV);
        gradeTV = findViewById(R.id.studentReportCard_gradeTV);
        gradeHeaderTV = findViewById(R.id.studentReportCard_gradeHeaderTV);


        examNameTV.setText(getIntent().getStringExtra("examName"));
        examNameTV.setBackgroundColor(Color.parseColor(Utility.getSharedPreferences(getApplicationContext(), Constants.secondaryColour)));

        adapter = new StudentReportCard_ExamResultAdapter(StudentReportCard_ExamResult.this,
                examSubjectList, examPassingMarksList, examObtainedMarksList, examResultList);
        RecyclerView.LayoutManager mLayoutManager = new LinearLayoutManager(getApplicationContext());
        examResultview.setLayoutManager(mLayoutManager);
        examResultview.setItemAnimator(new DefaultItemAnimator());
        examResultview.setAdapter(adapter);

        params.put("student_id", Utility.getSharedPreferences(getApplicationContext(), "studentId"));
        params.put("exam_id", getIntent().getStringExtra("examId"));
        JSONObject obj=new JSONObject(params);
        Log.e("params ", obj.toString());

        getDataFromApi(obj.toString());

    }

    private void getDataFromApi (String bodyParams) {

        final ProgressDialog pd = new ProgressDialog(this);
        pd.setMessage("Loading");
        pd.setCancelable(false);
        pd.show();

        final String requestBody = bodyParams;

        String url = Utility.getSharedPreferences(getApplicationContext(), "apiUrl")+Constants.getExamResultDetailsUrl;
        StringRequest stringRequest = new StringRequest(Request.Method.POST, url, new Response.Listener<String>() {
            @Override
            public void onResponse(String result) {
                if (result != null) {
                    pd.dismiss();
                    try {
                        Log.e("Result", result);
                        JSONObject object = new JSONObject(result);

                        String success = object.getString("status");
                        if (success.equals("200")) {

                            totalTV.setText(object.getString("get_marks") + "/" + object.getString("total_marks"));
                            percentageTV.setText(object.getString("percentage"));
                            String grade = object.getString("grade");
                            Log.e("grade", grade);
                            if(grade.equals("empty list")) {
                                gradeTV.setVisibility(View.GONE);
                                gradeHeaderTV.setVisibility(View.GONE);
                                Log.e("grade", "gone");
                            } else {
                                gradeTV.setVisibility(View.VISIBLE);
                                gradeHeaderTV.setVisibility(View.VISIBLE);
                            }
                            gradeTV.setText(grade);
                            String status = object.getString("result");
                            if(status.toLowerCase().equals("pass")) {
                                statusTV.setBackgroundResource(R.drawable.green_border);
                            } else {
                                statusTV.setBackgroundResource(R.drawable.red_border);
                            }
                            statusTV.setText(status);

                            JSONArray dataArray = object.getJSONArray("exam_result");
                            for(int i=0; i<dataArray.length(); i++) {
                                examSubjectList.add(dataArray.getJSONObject(i).getString("exam_name"));
                                examPassingMarksList.add(dataArray.getJSONObject(i).getString("passing_marks"));

                                if(dataArray.getJSONObject(i).getString("attendence").equals("ABS")) {
                                    examObtainedMarksList.add(getApplicationContext().getString(R.string.absent)+"/"+dataArray.getJSONObject(i).getString("full_marks"));
                                } else {
                                    examObtainedMarksList.add(dataArray.getJSONObject(i).getString("get_marks")+"/"+dataArray.getJSONObject(i).getString("full_marks"));
                                }
                                examResultList.add(dataArray.getJSONObject(i).getString("status"));
                            }
                            adapter.notifyDataSetChanged();

                        } else {
                            Toast.makeText(getApplicationContext(), object.getString("errorMsg"), Toast.LENGTH_SHORT).show();
                        }
                    } catch (JSONException e) {
                        e.printStackTrace();
                    }
                } else {
                    pd.dismiss();
                    Toast.makeText(getApplicationContext(), R.string.noInternetMsg, Toast.LENGTH_SHORT).show();
                }
            }
        },
                new Response.ErrorListener() {
                    @Override
                    public void onErrorResponse(VolleyError volleyError) {
                        pd.dismiss();
                        Log.e("Volley Error", volleyError.toString());
                        Toast.makeText(StudentReportCard_ExamResult.this, R.string.slowInternetMsg, Toast.LENGTH_LONG).show();
                    }
                }) {

            @Override
            public Map<String, String> getHeaders() throws AuthFailureError {

                headers.put("Client-Service", Constants.clientService);
                headers.put("Auth-Key", Constants.authKey);
                headers.put("Content-Type", Constants.contentType);
                headers.put("User-ID", Utility.getSharedPreferences(getApplicationContext(), "userId"));
                headers.put("Authorization", Utility.getSharedPreferences(getApplicationContext(), "accessToken"));
                return headers;
            }

            @Override
            public String getBodyContentType() {
                return "application/json; charset=utf-8";
            }

            @Override
            public byte[] getBody() throws AuthFailureError {
                try {
                    return requestBody == null ? null : requestBody.getBytes("utf-8");
                } catch (UnsupportedEncodingException uee) {
                    VolleyLog.wtf("Unsupported Encoding while trying to get the bytes of %s using %s", requestBody, "utf-8");
                    return null;
                }
            }
        };
        //Creating a Request Queue
        RequestQueue requestQueue = Volley.newRequestQueue(StudentReportCard_ExamResult.this);

        //Adding request to the queue
        requestQueue.add(stringRequest);
    }

}
