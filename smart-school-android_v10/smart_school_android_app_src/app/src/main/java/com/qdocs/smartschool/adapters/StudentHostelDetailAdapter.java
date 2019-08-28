package com.qdocs.smartschool.adapters;

import android.support.v7.widget.RecyclerView;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.TextView;

import com.qdocs.smartschool.R;
import com.qdocs.smartschool.students.StudentHostel;

import java.util.ArrayList;

public class StudentHostelDetailAdapter extends RecyclerView.Adapter<StudentHostelDetailAdapter.MyViewHolder> {

    private StudentHostel context;
    private ArrayList<String> roomTypeList;
    private ArrayList<String> roomNumberList;
    private ArrayList<String> roomCostList;

    public StudentHostelDetailAdapter(StudentHostel studentHostelDetail, ArrayList<String> roomTypeList,
                                      ArrayList<String> roomNumberList, ArrayList<String> roomCostList) {

        this.context = studentHostelDetail;
        this.roomTypeList = roomTypeList;
        this.roomNumberList = roomNumberList;
        this.roomCostList = roomCostList;

    }

    public class MyViewHolder extends RecyclerView.ViewHolder {

        public TextView typeTV, numberTV, costTV;

        public MyViewHolder(View view) {
            super(view);

            typeTV = (TextView) view.findViewById(R.id.adapter_student_hostelDetail_roomTypeTV);
            numberTV = (TextView) view.findViewById(R.id.adapter_student_hostelDetail_roomNoTV);
            costTV = (TextView) view.findViewById(R.id.adapter_student_hostelDetail_roomCostTV);
        }
    }

    @Override
    public MyViewHolder onCreateViewHolder(ViewGroup parent, int viewType) {
        View itemView = LayoutInflater.from(parent.getContext())
                .inflate(R.layout.adapter_student_hostel_detail, parent, false);

        return new MyViewHolder(itemView);
    }

    @Override
    public void onBindViewHolder(final MyViewHolder holder, int position) {

        holder.typeTV.setText(roomTypeList.get(position));

        holder.numberTV.setText(roomNumberList.get(position));
        holder.costTV.setText(roomCostList.get(position));
    }

    @Override
    public int getItemCount() {
        return roomTypeList.size();
    }

}

