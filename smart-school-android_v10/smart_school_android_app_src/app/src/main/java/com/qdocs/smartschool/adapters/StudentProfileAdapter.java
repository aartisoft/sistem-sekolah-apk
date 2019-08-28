package com.qdocs.smartschool.adapters;

import android.content.Context;
import android.support.v7.widget.RecyclerView;
import android.util.Log;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.TextView;

import com.qdocs.smartschool.R;
import com.qdocs.smartschool.utils.Constants;
import com.qdocs.smartschool.utils.Utility;

import java.util.ArrayList;
import java.util.Locale;

public class StudentProfileAdapter extends RecyclerView.Adapter<StudentProfileAdapter.MyViewHolder> {

    private Context context;
    private int[] othersHeaderArray;
    private ArrayList<String> othersValues;

    public StudentProfileAdapter(Context applicationContext, int[] personalHeaderArray, ArrayList<String> personalValues) {

        this.context = applicationContext;
        this.othersHeaderArray = personalHeaderArray;
        this.othersValues = personalValues;

    }

    public class MyViewHolder extends RecyclerView.ViewHolder {
        public TextView headerTV, valueTV;

        public MyViewHolder(View view) {
            super(view);
            headerTV = (TextView) view.findViewById(R.id.adapter_student_profile_head);
            valueTV = (TextView) view.findViewById(R.id.adapter_student_profile_value);
        }
    }

    @Override
    public MyViewHolder onCreateViewHolder(ViewGroup parent, int viewType) {
        View itemView = LayoutInflater.from(parent.getContext())
                .inflate(R.layout.adapter_student_profile, parent, false);

        return new MyViewHolder(itemView);
    }

    @Override
    public void onBindViewHolder(MyViewHolder holder, int position) {

        Utility.setLocale(context, Utility.getSharedPreferences(context.getApplicationContext(), Constants.langCode));

        holder.headerTV.setText(context.getString(othersHeaderArray[position]));
        holder.valueTV.setText(othersValues.get(position));
    }

    @Override
    public int getItemCount() {
        return othersValues.size();
    }
}