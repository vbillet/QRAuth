package fr.billet.vincent.security;

import android.content.pm.PackageManager;

import androidx.appcompat.app.AppCompatActivity;
import androidx.core.app.ActivityCompat;
import androidx.core.content.ContextCompat;

import java.lang.reflect.Method;
import java.util.Vector;

public class permissionChecker {
    private AppCompatActivity app;
    private Vector<String> permissions;
    private Vector<Boolean> granted;
    public permissionChecker(AppCompatActivity act){
        app=act;
        permissions = new Vector<>(0);
        granted = new Vector<>(0);
    }

    public void addPermission(String Permission){
        permissions.add(Permission);
        granted.add(false);
    }

    public void checkPermissions(){
        for(int ii=0;ii<permissions.size();ii++){
            if (!granted.get(ii)) {
                if (ContextCompat.checkSelfPermission(app.getApplicationContext(), permissions.get(ii)) != PackageManager.PERMISSION_GRANTED) {
                    ActivityCompat.requestPermissions(app, new String[]{permissions.get(ii)}, ii);
                    return;
                } else {
                    granted.set(ii, true);
                }
            }
        }
        permissionsChecked();
    }
    public void onRequestPermissionsResult(int requestCode,String[] permissions, int[] grantResults) {
        if (grantResults.length > 0 && grantResults[0] == PackageManager.PERMISSION_GRANTED) {
            granted.set(grantResults[0],true);
            checkPermissions();
        } else {
            permissionsRefused();
        }
    }
    private void permissionsChecked(){
        try{
            Method m = app.getClass().getMethod("onPermissionsChecked");
            m.invoke(app);
        } catch (Exception e){
            // Nothing
        }
    }
    private void permissionsRefused(){
        try{
            Method m = app.getClass().getMethod("onPermissionsRefused");
            m.invoke(app);
        } catch (Exception e){
            // Nothing
        }
    }
}
