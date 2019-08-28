package com.qdocs.smartschool.adapters;

import android.support.v7.widget.RecyclerView;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.TextView;

import com.qdocs.smartschool.R;
import com.qdocs.smartschool.students.StudentReportCard_ExamResult;

import java.util.ArrayList;

public class StudentReportCard_ExamResultAdapter extends RecyclerView.Adapter<StudentReportCard_ExamResultAdapter.MyViewHolder> {

    private StudentReportCard_ExamResult context;
    private ArrayList<String> examSubjectList;
    private ArrayList<String> examPassingMarksList;
    private ArrayList<String> examObtainedMarksList;
    private ArrayList<String> examResultList;

    public StudentReportCard_ExamResultAdapter(StudentReportCard_ExamResult studentReportCard_examResult,
                                               ArrayList<String> examSubjectList, ArrayList<String> examPassingMarksList,
                                               ArrayList<String> examObtainedMarksList, ArrayList<String> examResultList) {
        this.context = studentReportCard_examResult;
        this.examSubjectList = examSubjectList;
        this.examPassingMarksList = examPassingMarksList;
        this.examObtainedMarksList = examObtainedMarksList;
        this.examResultList = examResultList;
    }

    public class MyViewHolder extends RecyclerView.ViewHolder {
        public TextView subjectNameTV, passngMarksTV, obtainedMarksTV, resultTV;

        public MyViewHolder(View view) {
            super(view);
            subjectNameTV = (TextView) view.findViewById(R.id.adapter_reportCard_examResult_subjectNameTV);
            passngMarksTV = (TextView) view.findViewById(R.id.adapter_reportCard_examResult_passingmarksTV);
            obtainedMarksTV = (TextView) view.findViewById(R.id.adapter_reportCard_examResult_marksObtainedTV);
            resultTV = (TextView) view.findViewById(R.id.adapter_reportCard_examResult_resultTV);
        }
    }

    @Override
    public MyViewHolder onCreateViewHolder(ViewGroup parent, int viewType) {
        View itemView = LayoutInflater.from(parent.getContext())
                .inflate(R.layout.adapter_report_card_exam_result, parent, false);

        return new MyViewHolder(itemView);
    }

    @Override
    public void onBindViewHolder(MyViewHolder holder, int position) {
        holder.subjectNameTV.setText(examSubjectList.get(position));
        holder.passngMarksTV.setText(examPassingMarksList.get(position));
        holder.obtainedMarksTV.setText(examObtainedMarksList.get(position));
        holder.resultTV.setText(examResultList.get(position));

        if(examResultList.get(position).toLowerCase().equals("pass")) {
            holder.resultTV.setTextColor(context.getResources().getColor(R.color.green));
        } else if (examResultList.get(position).toLowerCase().equals("fail")) {
            holder.resultTV.setTextColor(context.getResources().getColor(R.color.red));
        }
    }

    @Override
    public int getItemCount() {
        return examSubjectList.size();
    }
}