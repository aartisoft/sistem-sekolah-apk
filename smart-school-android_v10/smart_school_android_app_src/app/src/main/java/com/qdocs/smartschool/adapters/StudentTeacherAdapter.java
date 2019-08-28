package com.qdocs.smartschool.adapters;

import android.content.Intent;
import android.graphics.Color;
import android.net.Uri;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.BaseAdapter;
import android.widget.RelativeLayout;
import android.widget.TextView;
import android.widget.Toast;

import com.qdocs.smartschool.R;
import com.qdocs.smartschool.students.StudentTeacher;
import com.qdocs.smartschool.utils.Constants;
import com.qdocs.smartschool.utils.Utility;

import java.util.ArrayList;

public class StudentTeacherAdapter extends BaseAdapter {

    private StudentTeacher context;
    private ArrayList<String> teacherNameList;
    private ArrayList<String> teacherEmailList;
    private ArrayList<String> teacherContactList;

    public StudentTeacherAdapter(StudentTeacher studentTeacher, ArrayList<String> teacherNameList,
                                 ArrayList<String> teacherEmailList, ArrayList<String> teacherContactList) {

        this.context = studentTeacher;
        this.teacherNameList = teacherNameList;
        this.teacherEmailList = teacherEmailList;
        this.teacherContactList = teacherContactList;

    }

    @Override
    public int getCount() {
        return teacherNameList.size();
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
            rowView = inflater.inflate(R.layout.adapter_student_teacher, viewGroup, false);
            viewHolder = new ViewHolder();

            viewHolder.teacherNameTV = (TextView) rowView.findViewById(R.id.studentTeacherAdapter_nameTV);
            viewHolder.teacherContactTV = (TextView) rowView.findViewById(R.id.studentTeacherAdapter_contactTV);
            viewHolder.teacherEmailTV = (TextView) rowView.findViewById(R.id.studentTeacherAdapter_emailTV);

            viewHolder.nameHeader = rowView.findViewById(R.id.adapter_student_teacher_nameHeader);

            viewHolder.teacherNameTV.setTag(position);
            viewHolder.teacherContactTV.setTag(position);
            viewHolder.teacherEmailTV.setTag(position);

        }else{
            viewHolder  = (ViewHolder) rowView.getTag();
        }

        viewHolder.teacherNameTV.setText(teacherNameList.get(position));
        viewHolder.teacherContactTV.setText(teacherContactList.get(position));
        viewHolder.teacherEmailTV.setText(teacherEmailList.get(position));

        viewHolder.nameHeader.setBackgroundColor(Color.parseColor(Utility.getSharedPreferences(context.getApplicationContext(), Constants.secondaryColour)));

        viewHolder.teacherEmailTV.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {

                Intent email = new Intent(Intent.ACTION_SEND);
                email.putExtra(Intent.EXTRA_EMAIL, new String[]{ teacherEmailList.get(position)});
                email.setType("message/rfc822");
                context.startActivity(Intent.createChooser(email, "Choose an Email client :"));
            }
        });

        viewHolder.teacherContactTV.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
            try {
                Intent intent = new Intent(Intent.ACTION_CALL);
                intent.setData(Uri.parse("tel:" + teacherContactList.get(position)));
                context.startActivity(intent);
            } catch (SecurityException se) {
                Toast.makeText(context.getApplicationContext(), "Calling Permission Not Granted!", Toast.LENGTH_LONG).show();
            }
            }
        });

        rowView.setTag(viewHolder);
        return rowView;

    }

    static class ViewHolder {
        private TextView teacherContactTV, teacherNameTV, teacherEmailTV;
        private RelativeLayout nameHeader;
    }
}
