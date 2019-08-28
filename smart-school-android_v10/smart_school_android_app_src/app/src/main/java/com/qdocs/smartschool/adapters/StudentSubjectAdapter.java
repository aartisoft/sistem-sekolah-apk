package com.qdocs.smartschool.adapters;

import android.graphics.Color;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.BaseAdapter;
import android.widget.RelativeLayout;
import android.widget.TextView;

import com.qdocs.smartschool.R;
import com.qdocs.smartschool.students.StudentSubject;
import com.qdocs.smartschool.utils.Constants;
import com.qdocs.smartschool.utils.Utility;

import java.util.ArrayList;

public class StudentSubjectAdapter extends BaseAdapter {

    private StudentSubject context;
    private ArrayList<String> subjectNameList;
    private ArrayList<String> subjectCodeList;
    private ArrayList<String> subjectTeacherNameList;
    private ArrayList<String> subjectTypeList;

    public StudentSubjectAdapter(StudentSubject studentsSubject, ArrayList<String> subjectNameList,
                                 ArrayList<String> subjectCodeList, ArrayList<String> subjectTeacherNameList,
                                 ArrayList<String> subjectTypeList) {

        this.context = studentsSubject;
        this.subjectNameList = subjectNameList;
        this.subjectCodeList = subjectCodeList;
        this.subjectTeacherNameList = subjectTeacherNameList;
        this.subjectTypeList = subjectTypeList;

    }

    @Override
    public int getCount() {
        return subjectNameList.size();
    }

    @Override
    public Object getItem(int i) {
        return null;
    }

    @Override
    public long getItemId(int i) {
        return 0;
    }

    @Override
    public View getView(final int position, View view, ViewGroup viewGroup) {
        View rowView = view;
        ViewHolder viewHolder = null;

        if (rowView == null) {

            LayoutInflater inflater = LayoutInflater.from(context);
            rowView = inflater.inflate(R.layout.adapter_student_subject, viewGroup, false);
            viewHolder = new ViewHolder();

            viewHolder.subjectNameTV = (TextView) rowView.findViewById(R.id.studentSubjectAdapter_nameTV);
            viewHolder.teacherNameTV = (TextView) rowView.findViewById(R.id.studentSubjectAdapter_teacherNameTV);
            viewHolder.subjectCodeTV = (TextView) rowView.findViewById(R.id.studentSubjectAdapter_codeTV);
            viewHolder.subjectTypeTV = (TextView) rowView.findViewById(R.id.studentSubjectAdapter_subjectType);
            viewHolder.nameHeader = rowView.findViewById(R.id.adapter_student_subject_nameHeader);

            viewHolder.subjectNameTV.setTag(position);
            viewHolder.teacherNameTV.setTag(position);
            viewHolder.subjectCodeTV.setTag(position);

        }else{
            viewHolder  = (ViewHolder) rowView.getTag();
        }

        viewHolder.subjectNameTV.setText(subjectNameList.get(position));
        viewHolder.subjectTypeTV.setText(subjectTypeList.get(position));
        viewHolder.teacherNameTV.setText(subjectTeacherNameList.get(position));
        viewHolder.subjectCodeTV.setText("Code : " + subjectCodeList.get(position));

        viewHolder.nameHeader.setBackgroundColor(Color.parseColor(Utility.getSharedPreferences(context.getApplicationContext(), Constants.secondaryColour)));

        rowView.setTag(viewHolder);
        return rowView;

    }

    static class ViewHolder {
        private TextView subjectNameTV, teacherNameTV, subjectCodeTV, subjectTypeTV;
        private RelativeLayout nameHeader;
    }
}
