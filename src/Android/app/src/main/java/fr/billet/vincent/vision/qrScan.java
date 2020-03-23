package fr.billet.vincent.vision;

import android.Manifest;
import android.content.Context;
import android.content.pm.PackageManager;
import android.util.Log;
import android.util.SparseArray;
import android.view.SurfaceHolder;
import android.view.SurfaceView;

import androidx.core.app.ActivityCompat;

import com.google.android.gms.vision.CameraSource;
import com.google.android.gms.vision.Detector;
import com.google.android.gms.vision.barcode.Barcode;
import com.google.android.gms.vision.barcode.BarcodeDetector;

import java.io.IOException;
import java.lang.reflect.Method;


/**
 * AJOUTER AU build.gradle (Module:app) :
 * dependencies {
 *         // google vision gradle
 *         implementation 'com.google.android.gms:play-services-vision:15.0.2'
 * }
 * AJOUTER au Manifest (dans Application)
 *         <meta-data
 *             android:name="com.google.android.gms.version"
 *             android:value="@integer/google_play_services_version" />
 *         <meta-data
 *             android:name="com.google.android.gms.vision.DEPENDENCIES"
 *             android:value="barcode" />
 * AJOUTER au Manifest :
 *     <uses-permission android:name="android.permission.CAMERA"/>
 */
public class qrScan  {
    private SurfaceView cameraPreview;
    protected static Context context;
    protected static Object app;
    private static SurfaceHolder.Callback surfaceCB;
    private CameraSource cameraSource;
    private BarcodeDetector barcodeDetector;
    public qrScan(Object obj, Context ctx, SurfaceView surface){
        cameraPreview = surface;
        context = ctx;
        app = obj;
    }
    /*@Override
    protected void onCreate(Bundle savedInstanceState){
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_scan_barcode);
        cameraPreview = (SurfaceView)findViewById(R.id.camera_preview);
        createCameraSource();
    }*/
    public void scan(){
        createCameraSource();
    }
    public void releaseAndCleanup() {
        stop();
        if (cameraSource != null) {
            //release camera and barcode detector(will invoke inside) resources
            cameraSource.release();
            cameraSource = null;
        }
    }

    /**
     * Stop camera
     */
    public void stop() {
        try {
            if (cameraSource != null) {
                cameraSource.stop();
            }
        } catch (Exception ie) {
            Log.e("QRScan", ie.getMessage());
            ie.printStackTrace();
        }
    }
    //public void setContentView(Layout layout){ setContentView(layout); }
    private void createCameraSource(){
        barcodeDetector = new BarcodeDetector.Builder(context).build();
        cameraSource = new CameraSource.Builder(context,barcodeDetector)
                .setAutoFocusEnabled(true)
                .setRequestedPreviewSize(1600,1024)
                .build();

        surfaceCB = new SurfaceHolder.Callback() {
            @Override
            public void surfaceCreated(SurfaceHolder holder) {
                if (ActivityCompat.checkSelfPermission(qrScan.context, Manifest.permission.CAMERA)!= PackageManager.PERMISSION_GRANTED){
                    return;
                }
                try {
                    cameraSource.start(cameraPreview.getHolder());
                } catch (IOException e) {
                    e.printStackTrace();
                }
            }

            @Override
            public void surfaceChanged(SurfaceHolder holder, int format, int width, int height) {

            }

            @Override
            public void surfaceDestroyed(SurfaceHolder holder) {
                try {
                    cameraSource.stop();
                } catch (Exception e){
                    // Nothing
                }
            }
        };
        cameraPreview.getHolder().addCallback(surfaceCB);
        barcodeDetector.setProcessor(new Detector.Processor<Barcode>() {
            @Override
            public void release() { }

            @Override
            public void receiveDetections(Detector.Detections<Barcode> detections) {
                final SparseArray<Barcode> barcodes=detections.getDetectedItems();
                if(barcodes.size()>0){
                    //Log.i("QRScan",barcodes.valueAt(0).displayValue);
                    try{
                        Method m = qrScan.app.getClass().getMethod("onScan",String.class);
                        m.invoke(qrScan.app,barcodes.valueAt(0).displayValue);
                    } catch (Exception e){
                        // Nothing
                    }

                    /*setResult(CommonStatusCodes.SUCCESS,intent);
                    finish();*/
                }
            }
        });
    }
}
