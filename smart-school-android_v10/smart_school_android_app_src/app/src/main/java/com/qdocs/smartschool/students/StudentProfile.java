package com.qdocs.smartschool.students;

import android.app.ProgressDialog;
import android.graphics.Color;
import android.os.Bundle;
import android.support.design.widget.TabLayout;
import android.support.v4.app.Fragment;
import android.support.v4.app.FragmentManager;
import android.support.v4.app.FragmentPagerAdapter;
import android.support.v4.view.ViewPager;
import android.util.Log;
import android.view.LayoutInflater;
import android.view.View;
import android.widget.ImageView;
import android.widget.RelativeLayout;
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
import com.qdocs.smartschool.adapters.StudentProfileAdapter;
import com.qdocs.smartschool.fragments.StudentProfileParentFragment;
import com.qdocs.smartschool.fragments.StudentProfilePersonalFragment;
import com.qdocs.smartschool.utils.Constants;
import com.qdocs.smartschool.utils.Utility;
import com.squareup.picasso.Picasso;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import java.io.UnsupportedEncodingException;
import java.util.ArrayList;
import java.util.HashMap;
import java.util.Hashtable;
import java.util.List;
import java.util.Locale;
import java.util.Map;

public class StudentProfile extends BaseActivity {

    private TabLayout tabLayout;
    private ViewPager viewPager;

    int[] personalHeaderArray = {R.string.admDate, R.string.dob, R.string.category, R.string.mobileNo,
            R.string.caste, R.string.religion, R.string.email, R.string.currentAdd, R.string.permanentAdd, R.string.bloodGroup,
            R.string.height,R.string.weight, R.string.asOnDate};

    int[] othersHeaderArray = {R.string.previousSchool, R.string.nationalIdNo, R.string.localIdNo,
            R.string.bankAcNo, R.string.bankName, R.string.ifscCode, R.string.rte, R.string.studentHouse, R.string.vehicleRoute,
            R.string.vehicleNo, R.string.driverName, R.string.driverContact, R.string.hostel,
            R.string.hostelRoomNo, R.string.hostelRoomType};


    ArrayList<String> personalValues = new ArrayList<String>();
    HashMap<String, String> parentsValues = new HashMap<>();
    ArrayList<String> othersValues = new ArrayList<String>();

    TextView nameTV, admissionNoTV, classTV, rollNoTV;
    ImageView profileIV;

    RelativeLayout headerLayout;

    public Map<String, String> headers = new HashMap<String, String>();
    public Map<String, String> params = new Hashtable<String, String>();

    StudentProfileAdapter adapter;
    ProfileViewPagerAdapter viewPagerAdapter;


    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        LayoutInflater inflater = (LayoutInflater) this.getSystemService(LAYOUT_INFLATER_SERVICE);
        View contentView = inflater.inflate(R.layout.student_profile_activity, null, false);
        mDrawerLayout.addView(contentView, 0);

        titleTV.setText(getApplicationContext().getString(R.string.profile));

        viewPager = (ViewPager) findViewById(R.id.profileViewPager);
        profileIV = (ImageView) findViewById(R.id.studentProfile_profileImageview);
        nameTV = (TextView) findViewById(R.id.studentProfile_nameTV);
        admissionNoTV = (TextView) findViewById(R.id.studentProfile_admissionNoTV);
        rollNoTV = (TextView) findViewById(R.id.studentProfile_rollNoTV);
        classTV = (TextView) findViewById(R.id.studentProfile_classTV);
        headerLayout = findViewById(R.id.profile_headerLayout);

        viewPagerAdapter = new ProfileViewPagerAdapter(getSupportFragmentManager());

        tabLayout = (TabLayout) findViewById(R.id.profileTabLayout);
        tabLayout.setupWithViewPager(viewPager);

        params.put("studentId", Utility.getSharedPreferences(getApplicationContext(), "studentId"));
        JSONObject obj=new JSONObject(params);
        Log.e("params ", obj.toString());

        getDataFromApi(obj.toString());

        decorate();


        Locale current = getResources().getConfiguration().locale;
        Log.e("current locale", current.getDisplayName()+"..");


    }

    private void decorate () {
        headerLayout.setBackgroundColor(Color.parseColor(Utility.getSharedPreferences(getApplicationContext(), Constants.secondaryColour)));
        tabLayout.setSelectedTabIndicatorColor(Color.parseColor(Utility.getSharedPreferences(getApplicationContext(), Constants.primaryColour)));
    }

    private void getDataFromApi (String bodyParams) {

        final ProgressDialog pd = new ProgressDialog(this);
        pd.setMessage("Loading");
        pd.setCancelable(false);
        pd.show();

        final String requestBody = bodyParams;

        String url = Utility.getSharedPreferences(getApplicationContext(), "apiUrl")+Constants.getStudentProfileUrl;
        StringRequest stringRequest = new StringRequest(Request.Method.POST, url, new Response.Listener<String>() {
            @Override
            public void onResponse(String result) {
                if (result != null) {
                    pd.dismiss();
                    try {
                        Log.e("Result", result);
                        JSONObject object = new JSONObject(result);

                        String success = object.getString("success");
                        if (success.equals("1")) {

                            JSONArray dataArray = object.getJSONArray("data");
                            JSONObject data = dataArray.getJSONObject(0);

                            nameTV.setText(data.getString("firstname") + " " + data.getString("lastname") );
                            admissionNoTV.setText(data.getString("admission_no"));
                            rollNoTV.setText(data.getString("roll_no"));
                            classTV.setText(data.getString("class") + " - " + data.getString("section"));
//                            rteTV.setText(data.getString("rte"));


                            personalValues.add(Utility.parseDate("yyyy-MM-dd", defaultDateFormat, data.getString("admission_date")));
                            personalValues.add(Utility.parseDate("yyyy-MM-dd", defaultDateFormat, data.getString("dob")));
                            personalValues.add(checkData(data.getString("category")));
                            personalValues.add(checkData(data.getString("mobileno")));
                            personalValues.add(checkData(data.getString("cast")));
                            personalValues.add(checkData(data.getString("religion")));
                            personalValues.add(checkData(data.getString("email")));
                            personalValues.add(checkData(data.getString("current_address")));
                            personalValues.add(checkData(data.getString("permanent_address")));
                            personalValues.add(checkData(data.getString("blood_group")));
                            personalValues.add(checkData(data.getString("height")));
                            personalValues.add(checkData(data.getString("weight")));
                            personalValues.add(Utility.parseDate("yyyy-MM-dd", defaultDateFormat, data.getString("measurement_date")));


                            parentsValues.put("father_name", checkData(data.getString("father_name")));
                            parentsValues.put("father_phone", checkData(data.getString("father_phone")));
                            parentsValues.put("father_occupation", checkData(data.getString("father_occupation")));
                            parentsValues.put("father_image", checkData(data.getString("father_pic")));
                            parentsValues.put("mother_name", checkData(data.getString("mother_name")));
                            parentsValues.put("mother_phone", checkData(data.getString("mother_phone")));
                            parentsValues.put("mother_occupation", checkData(data.getString("mother_occupation")));
                            parentsValues.put("mother_image", checkData(data.getString("mother_pic")));
                            parentsValues.put("guardian_name", checkData(data.getString("guardian_name")));
                            parentsValues.put("guardian_email", checkData(data.getString("guardian_email")));
                            parentsValues.put("guardian_relation", checkData(data.getString("guardian_relation")));
                            parentsValues.put("guardian_phone", checkData(data.getString("guardian_phone")));
                            parentsValues.put("guardian_occupation", checkData(data.getString("guardian_occupation")));
                            parentsValues.put("guardian_address", checkData(data.getString("guardian_address")));
                            parentsValues.put("guardian_image", checkData(data.getString("guardian_pic")));


                            //name, mobile, profession, relation, email, address

                            othersValues.add(checkData(data.getString("previous_school")));
                            othersValues.add(checkData(data.getString("adhar_no")));
                            othersValues.add(checkData(data.getString("samagra_id")));
                            othersValues.add(checkData(data.getString("bank_account_no")));
                            othersValues.add(checkData(data.getString("bank_name")));
                            othersValues.add(checkData(data.getString("ifsc_code")));
                            othersValues.add(checkData(data.getString("rte")));
                            othersValues.add(checkData(data.getString("house_name")));
                            othersValues.add(checkData(data.getString("route_title")));

                            othersValues.add(checkData(data.getString("vehicle_no")));
                            othersValues.add(checkData(data.getString("driver_name")));
                            othersValues.add(checkData(data.getString("driver_contact")));
                            othersValues.add(checkData(data.getString("hostel_name")));
                            othersValues.add(checkData(data.getString("room_no")));
                            othersValues.add(checkData(data.getString("room_type")));

                            String imgUrl = Utility.getSharedPreferences(getApplicationContext(), "imagesUrl") + data.getString("image");
                            Picasso.with(getApplicationContext()).load(imgUrl).placeholder(R.drawable.placeholder_user).into(profileIV);
                            setupViewPager(viewPager);

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
                        Toast.makeText(StudentProfile.this, R.string.slowInternetMsg, Toast.LENGTH_LONG).show();
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
        RequestQueue requestQueue = Volley.newRequestQueue(StudentProfile.this);

        //Adding request to the queue
        requestQueue.add(stringRequest);
    }

    private String checkData(String key) {
        if(key.equals("null")) {
            return "";
        } else {
            return key;
        }
    }


    private void setupViewPager(ViewPager viewPager) {
        viewPagerAdapter.addFragment(new StudentProfilePersonalFragment().newInstance(personalHeaderArray, personalValues), getApplicationContext().getString(R.string.personalDetail));
        viewPagerAdapter.addFragment(new StudentProfileParentFragment().newInstance(parentsValues), getApplicationContext().getString(R.string.parentsDetails));
        viewPagerAdapter.addFragment(new StudentProfilePersonalFragment().newInstance(othersHeaderArray, othersValues), getApplicationContext().getString(R.string.otherDetails));
        viewPager.setAdapter(viewPagerAdapter);
    }

    class ProfileViewPagerAdapter extends FragmentPagerAdapter {
        private final List<Fragment> mFragmentList = new ArrayList<>();
        private final List<String> mFragmentTitleList = new ArrayList<>();

        public ProfileViewPagerAdapter(FragmentManager manager) {
            super(manager);
        }

        @Override
        public Fragment getItem(int position) {
            return mFragmentList.get(position);
        }

        @Override
        public int getCount() {
            return mFragmentList.size();
        }

        public void addFragment(Fragment fragment, String title) {
            mFragmentList.add(fragment);
            mFragmentTitleList.add(title);
        }

        @Override
        public CharSequence getPageTitle(int position) {
            return mFragmentTitleList.get(position);
        }
    }
}


