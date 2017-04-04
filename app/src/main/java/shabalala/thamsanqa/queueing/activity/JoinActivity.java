package shabalala.thamsanqa.queueing.activity;

import android.content.Intent;
import android.content.SharedPreferences;
import android.support.v7.app.AppCompatActivity;
import android.os.Bundle;
import android.view.View;
import android.widget.Button;
import android.widget.RadioButton;
import android.widget.RadioGroup;
import android.widget.Toast;

import com.android.volley.AuthFailureError;
import com.android.volley.Request;
import com.android.volley.RequestQueue;
import com.android.volley.Response;
import com.android.volley.VolleyError;
import com.android.volley.toolbox.JsonObjectRequest;
import com.android.volley.toolbox.StringRequest;
import com.android.volley.toolbox.Volley;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import java.util.HashMap;
import java.util.Map;


import shabalala.thamsanqa.queueing.R;
import shabalala.thamsanqa.queueing.app.Config;

public class JoinActivity extends AppCompatActivity {

    private Button  Queue;
    private RadioGroup radioGroup;
    private RadioButton radioButton;

    protected RequestQueue requestQueue;
    protected String insertUrl = "http://thammy202.comli.com/add_queue.php";
    protected String showUrl = "http://thammy202.comli.com/get_all_queues.php";

    protected String Token = "";


    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_join);

        SharedPreferences pref = getApplicationContext().getSharedPreferences(Config.SHARED_PREF, 0);
        Token = pref.getString("regId", null);

        requestQueue = Volley.newRequestQueue(getApplicationContext());

        radioGroup = (RadioGroup)findViewById(R.id.rbGroupGender);
        Queue = (Button)findViewById(R.id.btn_queue);


        Queue.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {

                try {

                    int GenderChoice = radioGroup.getCheckedRadioButtonId();
                    radioButton = (RadioButton)findViewById(GenderChoice);
                    final String  Gender = radioButton.getText().toString();

                    StringRequest request = new StringRequest(Request.Method.POST, insertUrl, new Response.Listener<String>() {

                        @Override
                        public void onResponse(String response) {

                            System.out.println(response.toString());
                        }
                    }, new Response.ErrorListener() {
                        @Override
                        public void onErrorResponse(VolleyError error) {

                        }
                    }) {

                        @Override
                        protected Map<String, String> getParams() throws AuthFailureError {
                            Map<String,String> parameters  = new HashMap<String, String>();
                            parameters.put("Gender",Gender);
                            parameters.put("Token",Token);
                            return parameters;
                        }
                    };
                    requestQueue.add(request);
                    Toast.makeText(getApplicationContext(),"You are on the queue",Toast.LENGTH_LONG).show();

                }catch (Exception e){

                    System.out.println(e.getCause().toString());
                }

            }

        });

        check();

    }

    public void check(){

        JsonObjectRequest jsonObjectRequest = new JsonObjectRequest(Request.Method.POST,
                showUrl, new Response.Listener<JSONObject>() {
            @Override
            public void onResponse(JSONObject response) {
                System.out.println(response.toString());
                try {
                    JSONArray y = response.getJSONArray("users");
                    for (int i = 0; i < y.length(); i++) {
                        JSONObject t = y.getJSONObject(i);

                        String token = t.getString("Token");
                        if(Token.equalsIgnoreCase(token)){
                            startActivity(new Intent(JoinActivity.this,MainActivity.class));
                        }
                    }

                } catch (JSONException e) {
                    e.printStackTrace();
                }

            }
        }, new Response.ErrorListener() {
            @Override
            public void onErrorResponse(VolleyError error) {
                System.out.append(error.getMessage());

            }
        });
        requestQueue.add(jsonObjectRequest);

    }

}
