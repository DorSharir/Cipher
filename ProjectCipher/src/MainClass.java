import com.sun.deploy.security.SelectableSecurityManager;
import java.io.*;
import java.nio.file.Files;

public class MainClass {
    public static void main(String[] args) {

        try {
            /*
             * In switch we are checking args[1], this is a type of action
             * that wee receive from PHP.
             * In some cases, we sending back to PHP output,
             * like errors, message to input a key and ect.
             */
            switch (Integer.parseInt(args[1])) {
                // PHP sends normal encryption
                case 0:
                    UserFileNormal inFile = new UserFileNormal(args[0], args[2], Integer.parseInt(args[3]));
                    inFile.normalEncrypt();
                    System.out.println("0");                    // Sending to PHP, that everything is good
                    System.out.println(inFile.getKey());        // Sending to PHP an encryption key
                    break;
                // PHP sends strong encrypt
                case 1:
                    UserFileStrong inputFile = new UserFileStrong(args[0], Integer.parseInt(args[3]), args[2]);
                    inputFile.strongEncrypt();
                    System.out.println("0");        // Sending to PHP, that everything is good
                                                    // and waiting for key from user
                    break;
                // Decryption of both, strong and normal
                case 2:
                    // Checking if file has .seqr extension
                    if (!args[0].substring(args[0].lastIndexOf('.')).equals(".seqr")) {
                        System.out.println("3");        // Sending to PHP message, that file is not our
                        return;
                    }
                    // After checking extension, opening the file to check the last bit,
                    // 0 - normal encryption, 1 -strong encryption, anything else is not our
                    FileInputStream tempFile = new FileInputStream("C:/xampp/htdocs/Cipher/upload/" + args[0]);
                    byte[] decrFileBytes = new byte[(Integer.parseInt(args[3]))];
                    tempFile.read(decrFileBytes);
                    tempFile.close();
                    // Checking if file has normal encryption, with last bit of file
                    if (decrFileBytes[Integer.parseInt(args[3]) - 1] == '0') {
                        UserFileNormal decNormFile = new UserFileNormal(decrFileBytes, args[0], args[2]);
                        // Checking if user is not the owner of the file
                        if (decNormFile.normalDecrypt() == 1) {
                            System.out.println("2");        // Sending to PHP message to open a key popup
                            return;
                        }
                        // If user is owner of file
                        else {
                            System.out.println("0");                // Sending to PHP, that everything is good
                            System.out.print(decNormFile.getFileName());
                        }
                    }
                    //Checking if file has strong encryption, with last bit of file
                    else if (decrFileBytes[Integer.parseInt(args[3]) - 1] == '1') {
                        System.out.println("6");                   // Sending to PHP message to open a key popup
                        return;
                    }
                    // In case the file is not our's but has our extension
                    else {
                        System.out.println("3");            // Sending to PHP message, that file is not our
                        return;
                    }

                    break;
                // Decryption of friend's file using a key
                case 3:
                    UserFileNormal decWithKey = new UserFileNormal(args[0], Integer.parseInt(args[2]), args[3]);
                    // Checking an encryption key
                    if (decWithKey.decryptWithKey() == 1) {
                        System.out.println("4");            // Sending to PHP message, that key is wrong
                        return;
                    }
                    else {
                        System.out.println("0");
                        System.out.print(decWithKey.getFileName());
                        return;
                    }
                 // Showing key window after checking the email
                case 4:
                    if (!args[0].substring(args[0].lastIndexOf('.')).equals(".seqr")) {
                        System.out.println("3");            // In case file does not have ".seqr" extension
                        return;
                    }
                    UserFileNormal showKey = new UserFileNormal((int)(Integer.parseInt(args[3])- Math.pow(2,20)), args[0], args[2]);
                    int temp = showKey.showKey();
                    if(temp == 1) {
                        System.out.println("5");        // Sending to PHP, that everything is good
                        return;
                    }
                    else if(temp == 2) {
                        System.out.println("3");        // Sending to PHP error message
                        return;
                    }
                    else {
                        System.out.println("0");        // Sending to PHP, that everything is good
                        System.out.println(showKey.getKey());
                        return;
                    }
                 // Strong decryption
                case 5:
                    UserFileStrong decStrongFile = new UserFileStrong(args[0], Integer.parseInt(args[2]), args[3]);
                    decStrongFile.strongDecrypt();
                    System.out.println("0");            // Sending to PHP, that everything is good
                    System.out.println(decStrongFile.getFileName());

                    return;

                /***** FOR TESTS ONLY *****/
                case 7:
                    FileInputStream is = new FileInputStream("C:/xampp/htdocs/Cipher/upload/" + args[0]);
                    FileOutputStream os = new FileOutputStream("C:/xampp/htdocs/Cipher/upload/test1.seqr");
                    byte[] aByte = new byte[Integer.parseInt(args[2])];
                    is.read(aByte);
                    is.close();

                    os.write(aByte);
                    os.close();
            }
        }
        catch (Exception e) {
            e.printStackTrace();
        }
    }
}
