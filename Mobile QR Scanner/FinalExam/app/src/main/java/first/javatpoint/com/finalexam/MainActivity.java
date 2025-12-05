package first.javatpoint.com.finalexam;

import androidx.activity.result.ActivityResultLauncher;
import androidx.appcompat.app.AppCompatActivity;

import android.content.Intent;
import android.os.Bundle;
import android.view.View;
import android.widget.Button;
import android.widget.TextView;
import android.widget.Toast;

import com.google.firebase.auth.FirebaseAuth;
import com.google.firebase.auth.FirebaseUser;

import com.journeyapps.barcodescanner.CaptureActivity;
import com.journeyapps.barcodescanner.ScanContract;
import com.journeyapps.barcodescanner.ScanOptions;

public class MainActivity extends AppCompatActivity {

    FirebaseAuth auth;
    Button button;
    TextView textView;
    FirebaseUser user;
    private TextView resultTextView;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_main);

        // Initialize buttons and text views
        Button scanQRButton = findViewById(R.id.scanQRButton);
        Button transferButton = findViewById(R.id.transfer_button);
        Button payButton = findViewById(R.id.pay_button);
        Button topupButton = findViewById(R.id.topup_button);
        Button logoutButton = findViewById(R.id.logout);

        resultTextView = findViewById(R.id.resultTextView);

        // Initialize Firebase authentication and user info
        auth = FirebaseAuth.getInstance();
        textView = findViewById(R.id.user_details);
        user = auth.getCurrentUser();

        // Check if the user is logged in
        if (user == null) {
            Intent intent = new Intent(getApplication().getApplicationContext(), Login.class);
            startActivity(intent);
            finish();
        } else {
            textView.setText(user.getEmail());
        }

        // Set OnClickListener for the Logout button
        logoutButton.setOnClickListener(view -> {
            FirebaseAuth.getInstance().signOut();
            Intent intent = new Intent(getApplication().getApplicationContext(), Login.class);
            startActivity(intent);
            finish();
        });

        // Set OnClickListener for Scan QR button
        scanQRButton.setOnClickListener(view -> startQRScanner());

        // Set OnClickListener for Transfer button
        transferButton.setOnClickListener(view ->
                Toast.makeText(MainActivity.this, "Fetch not updated!", Toast.LENGTH_SHORT).show()
        );

        // Set OnClickListener for Pay button
        payButton.setOnClickListener(view ->
                Toast.makeText(MainActivity.this, "Fetch not updated!", Toast.LENGTH_SHORT).show()
        );

        // Set OnClickListener for Top-Up button
        topupButton.setOnClickListener(view ->
                Toast.makeText(MainActivity.this, "Fetch not updated!", Toast.LENGTH_SHORT).show()
        );
    }

    // QR Scanner functionality
    private void startQRScanner() {
        ScanOptions options = new ScanOptions();
        options.setPrompt("Place the QR code within the frame");
        options.setBeepEnabled(true);
        options.setOrientationLocked(true);
        options.setCaptureActivity(CaptureActivity.class);
        barcodeLauncher.launch(options);
    }

    private final ActivityResultLauncher<ScanOptions> barcodeLauncher = registerForActivityResult(
            new ScanContract(),
            result -> {
                if (result.getContents() != null) {
                    resultTextView.setText(result.getContents());
                } else {
                    resultTextView.setText("Scan cancelled");
                }
            }
    );
}
