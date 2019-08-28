package com.qdocs.smartschool.adapters;

import android.graphics.Color;
import android.support.v7.widget.RecyclerView;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.RelativeLayout;
import android.widget.TextView;

import com.qdocs.smartschool.R;
import com.qdocs.smartschool.students.StudentExamSchedule;
import com.qdocs.smartschool.utils.Constants;
import com.qdocs.smartschool.utils.Utility;

import java.util.ArrayList;

public class StudentExamScheduleAdapter extends RecyclerView.Adapter<StudentExamScheduleAdapter.MyViewHolder> {

    private StudentExamSchedule context;
    private ArrayList<String> subjectList;
    private ArrayList<String> dateList;

    private ArrayList<String> timeList;
    private ArrayList<String> roomList;


    public StudentExamScheduleAdapter(StudentExamSchedule studentExamSchedule, ArrayList<String> subjectList, ArrayList<String> dateList, ArrayList<String> timeList, ArrayList<String> roomList) {

        this.context = studentExamSchedule;
        this.subjectList = subjectList;
        this.dateList = dateList;
        this.timeList = timeList;
        this.roomList = roomList;
    }


    public class MyViewHolder extends RecyclerView.ViewHolder {

        public TextView subjectNameTV, dateTV, timeTV, roomNoTV;
        private RelativeLayout subjectNameHeader;

        public MyViewHolder(View view) {
            super(view);

            subjectNameHeader = view.findViewById(R.id.adapter_student_libraryBook_nameView);
            subjectNameTV = (TextView) view.findViewById(R.id.adapter_student_examSchedule_subjectTV);
            dateTV = (TextView) view.findViewById(R.id.adapter_student_examSchedule_dateTV);
            timeTV = (TextView) view.findViewById(R.id.adapter_student_examSchedule_timeTV);
            roomNoTV = (TextView) view.findViewById(R.id.adapter_student_examSchedule_roomTV);
        }
    }

    @Override
    public MyViewHolder onCreateViewHolder(ViewGroup parent, int viewType) {
        View itemView = LayoutInflater.from(parent.getContext())
                .inflate(R.layout.adapter_student_exam_schedule, parent, false);

        return new MyViewHolder(itemView);
    }

    @Override
    public void onBindViewHolder(final MyViewHolder holder, int position) {

        holder.subjectNameHeader.setBackgroundColor(Color.parseColor(Utility.getSharedPreferences(context.getApplicationContext(), Constants.secondaryColour)));

        holder.subjectNameTV.setText(subjectList.get(position));
        holder.dateTV.setText(dateList.get(position));
        holder.timeTV.setText(timeList.get(position));
        holder.roomNoTV.setText(roomList.get(position));

    }

    @Override
    public int getItemCount() {
        return subjectList.size();
    }


}
