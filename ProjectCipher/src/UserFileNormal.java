import java.io.*;
import java.util.Random;
import java.nio.file.Files;

public class UserFileNormal {

    FileInputStream inputStream;            // place where file will be taken
    FileOutputStream outputStream;          // place where file will be saved
    String key;                             // encryption key
    String friendsKey;                      // friend's encryption key
    String email;                           // user email
    String emailInFile;                     // email in file
    String oldExtension;                    // old extension in file
    int sizeOfFile;                         // size of file
    String fileName;                        // name of file
    byte[] allBytes;                        // array of all bytes of file

    /**
     * Constructor for normal encryption
     * @param fileName for file's name
     * @param email for user's email
     * @param size for file's size
     */
    public UserFileNormal(String fileName, String email, int size) throws Exception {
        this.fileName = fileName;
        this.email = email;
        this.emailInFile = "";// not used in encryption
        this.oldExtension = searchExtension(fileName);
        this.sizeOfFile = size;
        this.key = this.generateKey();      // creating a random key
        this.inputStream = new FileInputStream("C:/xampp/htdocs/Cipher/upload/" + fileName);
        this.outputStream = new FileOutputStream("C:/xampp/htdocs/Cipher/upload/" +
                                                    changeExtension(this.fileName, ".seqr"));
    }

    /**
     * Constructor for normal decryption
     * @param fileBytes for array of byte of file
     * @param fileName for file's name
     * @param userEmail for user's email
     */
    public UserFileNormal(byte[] fileBytes, String fileName, String userEmail) {
        this.allBytes = fileBytes;
        this.fileName = fileName;
        this.email = userEmail;
        this.sizeOfFile = fileBytes.length - (int) Math.pow(2, 20);     // length of inputted file without 1 MB
        this.key = "";
        this.emailInFile = "";
        this.oldExtension = "";
    }

    /**
     * Constructor for normal decrypt of friend's file
     * @param fileName for file's name
     * @param size for file's size
     * @param key for encryption key
     */
    public UserFileNormal(String fileName, int size, String key) {
        this.fileName = fileName;
        this.sizeOfFile = size;
        this.friendsKey=key;
    }

    /**
     * Constructor for Show Key
     * @param size for file's size
     * @param fileName for file's name
     * @param email for user's email
     */
    public UserFileNormal(int size, String fileName, String email) {
        this.fileName = fileName;
        this.sizeOfFile = size;
        this.email = email;
    }


    /**
     * Block of getters
     * @return respective value
     */
    public String getOldExtension() {
        return oldExtension;
    }
    public int getSizeOfFile() {
        return sizeOfFile;
    }
    public String getKey() {
        return key;
    }
    public String getFileName() {
        return fileName;
    }
    public String getEmail() {
        return email;
    }
    public String getEmailInFile() {
        return emailInFile;
    }

    /**
     * Method for searching extension of file
     * @param name for name of file
     * @return extension of file
     */
    private String searchExtension(String name) {
        String temp= new String();
        for (int i = name.lastIndexOf('.'); i < name.length(); i++)
            temp += name.charAt(i);
        return temp;
    }

    /**
     * Mrthod for changing an extension in file
     * @param name for name of file
     * @param sign name of file with extension after changing
     * @return
     */
    private String changeExtension(String name, String sign) {
        String tmp = name.substring(0, name.lastIndexOf('.'));
        return tmp + sign;
    }

    /**
     * Method for creating a random key
     * @return random string (key)
     */
    private String generateKey() {
        int length = 10;            // length of key
        String key = "";
        String salt = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz";
        Random rand = new Random();
        // loop each time takes a random char from salt
        for (int i = 0; i < length; i++)
            key += salt.charAt(rand.nextInt(62));
        return key;
    }

    /**
     * Method for adding 1 MB of random
     * chars to end of file
     */
    private void addMByte() {
        Random rand = new Random();
        for (int i = allBytes.length - (int) Math.pow(2, 20); i < allBytes.length; i++)
            allBytes[i] = (byte) (rand.nextInt(223) + 32);
    }

    /**
     * Method for removing 1 MB from end of file
     */
    private void removeMByte() {
        byte[] fileAfterDec = new byte[sizeOfFile];
        for (int i = 0; i < sizeOfFile; i++)
            fileAfterDec[i] = this.allBytes[i];
        this.allBytes = fileAfterDec;
    }

    /**
     * Method for hiding a data in file
     */
    private void hideAllData() {
        // hiding size of email in 2 first places
        allBytes[this.sizeOfFile] = (byte) ((email.length() / 10) + '0');
        allBytes[this.sizeOfFile + 1] = (byte) ((email.length() % 10) + '0');
        // hiding type of encryption (0 = normal) in the end of file
        allBytes[allBytes.length - 1] = '0';
        // hiding length of the extension and key in the end of file
        allBytes[allBytes.length - 2] = (byte) ((this.key + this.oldExtension).length() % 10 + '0');
        allBytes[allBytes.length - 3] = (byte) ((this.key + this.oldExtension).length() / 10 + '0');
        // hiding email at the start of added MB
        this.hideElement(sizeOfFile + 2, 1, email);
        // hiding extension and key in the end of added MB
        this.hideElement(allBytes.length - 4, -1, this.key + this.oldExtension);
    }

    /**
     * Method for hiding element inside the MB with starting point and jumps
     * @param start starting place
     * @param jump 1 - left to right, -1 - right to left
     * @param data string that need to hide
     */
    private void hideElement(int start, int jump, String data) {
        // loop that runs by even indexes of data
        for (int i = 0; i < data.length(); i += 2) {
            allBytes[start] = (byte) data.charAt(i);
            start += data.length() * jump;
        }
        // loop that runs by odd indexes of data
        for (int i = 1; i < data.length(); i += 2) {
            allBytes[start] = (byte) data.charAt(i);
            start += data.length() * jump;
        }
    }

    /**
     * Method for searching an email if file and
     * checking if user's email is equal to email in file
     * @return 1 - if not equal, 0 - if equal
     */
    private int checkEmails()
    {
        int sizeOfMail = (this.allBytes[this.sizeOfFile] - 48) * 10 + this.allBytes[this.sizeOfFile + 1] - 48;
        // searching email in file
        this.emailInFile = searchElement(sizeOfFile + 2, 1, sizeOfMail);
        // in case emails are not equal
        if(!this.email.equals(this.emailInFile))
            return 1;
        return 0;
    }

    /**
     * Method for checking an encryption key
     * @return 1 - if not equal, 0 - if equal
     */
    private int checkKeys()
    {
        int sizeOfKeyAndOldExtension = this.allBytes[this.allBytes.length - 2] - 48 +
                                                    (this.allBytes[this.allBytes.length - 3] - 48) * 10;
        String tempKeyAndExt = searchElement(allBytes.length - 4, -1, sizeOfKeyAndOldExtension);
        this.key = tempKeyAndExt.substring(0, 10);      // taking substring with length of 10, that is a key
        if(!this.key.equals(this.friendsKey))
            return 1;
        return 0;
    }

    /**
     * Method for searching all data in file
     * and changing file's extension.
     * First, searching SIZE of key and old extension, then searching
     * key and old extension
     * @return 0 if no error
     */
    private int searchAllData() {
        int sizeOfKeyAndOldExtension = this.allBytes[this.allBytes.length - 2] - 48 +
                                                    (this.allBytes[this.allBytes.length - 3] - 48) * 10;
        String tempKeyAndSign = searchElement(allBytes.length - 4, -1, sizeOfKeyAndOldExtension);
        this.key = tempKeyAndSign.substring(0, 10);
        this.oldExtension = tempKeyAndSign.substring(10);
        this.fileName= changeExtension(this.fileName,this.oldExtension);
        // no errors
        return 0;
    }

    /**
     * Same as hideElement method, but for searching
     * @param start starting place
     * @param jump 1 - left to right, 0 - right to left
     * @param sizeOfData size of string
     * @return hidden element
     */
    private String searchElement(int start, int jump, int sizeOfData) {
        byte[] tempOdd = new byte[sizeOfData / 2 + sizeOfData % 2];
        byte[] tempEven = new byte[sizeOfData / 2];
        int i, j;
        // loop for saving odd chars
        for (i = 0; i < tempOdd.length; i++, start += jump * sizeOfData)
            tempOdd[i] += allBytes[start];
        // loop for saving even chars
        for (j = 0; j < tempEven.length; j++, start += (sizeOfData * jump))
            tempEven[j] += allBytes[start];

        String foundData = "";
        String odd = new String(tempOdd);
        String even = new String(tempEven);
        // loop for creating a string with odd and even array of chars
        for (i = 0; i < tempEven.length; i++) {
            foundData += odd.charAt(i);
            foundData += even.charAt(i);
        }
        if (sizeOfData % 2 == 1)
            foundData += odd.charAt(i);

        return foundData;
    }

    /**
     * Method for normal encryption.
     * First, making encryption,
     * and in the and we are adding 1 MB
     * and hiding all data inside 1 MB
     */
    public void normalEncrypt() throws Exception {

        this.allBytes = new byte[(int) (this.sizeOfFile + Math.pow(2, 20))];
        inputStream.read(allBytes);
        inputStream.close();

        //**** encryption code ****//

        for (int i = 0; i < this.sizeOfFile; i++) {
            // making XOR between char in file and char in key
            int temp1 = (allBytes[i] ^ this.key.charAt(i % 10));
            // checking that char is not a control char
            if ( (allBytes[i] > 31 || allBytes[i] < 0) && (temp1 > 31 || temp1 < 0) &&
                                            allBytes[i] != 127 && temp1 != 127 &&
                                            allBytes[i] != 255 && temp1 != 255 ) {
                allBytes[i] = (byte)temp1;
            }
            // making multiplication by -1 with each byte
            int temp2 = (int)allBytes[i] * (-1);
            // checking that char is not a control char
            if ( (allBytes[i] > 31 || allBytes[i] < 0) && (temp2 > 31 || temp2 < 0) &&
                                            allBytes[i] != 127 && temp2 != 127 &&
                                            allBytes[i] != 255 && temp2 != 255 ) {
                allBytes[i] = (byte) temp2;
            }
        }

        // making rotate left k times (k is a first char in key)
        for(int k = 0; k < this.key.charAt(0); k++) {
            byte tempFirstByte = allBytes[0];
            int j;
            for (j = 1; j < this.sizeOfFile; j++) {
                allBytes[j - 1] = allBytes[j];
            }
            allBytes[this.sizeOfFile - 1] = tempFirstByte;
        }

        this.addMByte();
        hideAllData();
        outputStream.write(allBytes);
        outputStream.close();
    }

    /**
     * Method for normal decryption.
     * First, we are checking an equality of emails,
     * then we're searching all data and
     * removing 1 MB.
     * In the end, making decryption after all checks.
     * @return 1 - if emails are not equal, 0 - if there were no errors
     */
    public int normalDecrypt() throws IOException {
        if(checkEmails()==1)
            return 1;
        searchAllData();
        removeMByte();

        //**** decryption code ****//
        // rotate right k time (k is the first char in key)
        for(int k = 0; k < this.key.charAt(0); k++) {
            byte tempLastByte = allBytes[this.sizeOfFile - 1];
            int j;
            for (j = this.sizeOfFile - 1; j > 0; j--) {
                allBytes[j] = allBytes[j - 1];
            }
            allBytes[0] = tempLastByte;
        }

        for (int i = 0; i < this.sizeOfFile; i++) {
            // making multiplication by -1 with each byte
            int temp2 = (int)allBytes[i] * (-1);
            if ( (allBytes[i]>31 || allBytes[i]<0) && (temp2 >31 || temp2 < 0) &&
                                            allBytes[i] != 127 && temp2 != 127 &&
                                            allBytes[i] != 255 && temp2 != 255 ) {
                allBytes[i] = (byte)temp2;
            }
            // making XOR between char in file and char in key
            int temp1 = (allBytes[i] ^ this.key.charAt(i % 10));
            if ( (allBytes[i]>31 || allBytes[i]<0) && (temp1 >31 || temp1 < 0) &&
                                            allBytes[i] != 127 && temp1 != 127 &&
                                            allBytes[i] != 255 && temp1 != 255 ) {
                allBytes[i] = (byte)temp1;
            }
        }

        this.outputStream = new FileOutputStream("C:/xampp/htdocs/Cipher/upload/" +this.fileName);
        outputStream.write(allBytes);
        outputStream.close();
        return 0;
    }

    /**
     * Method for normal decryption of file with key.
     * First, checking the matching of keys.
     * Then, we use the same normal decryption algorithm
     * @return 1 if keys are not matching, o if no errors were found
     */
    public int decryptWithKey() throws Exception {
        this.inputStream = new FileInputStream("C:/xampp/htdocs/Cipher/upload/" + this.fileName);
        this.allBytes = new byte[this.sizeOfFile];
        inputStream.read(allBytes);
        inputStream.close();

        if(this.checkKeys()==1)
          return 1 ;

        searchAllData();
        this.sizeOfFile-=(int) Math.pow(2, 20);
        removeMByte();

        //**** decryption code ****//
        for(int k = 0; k < this.key.charAt(0); k++) {
            byte tempLastByte = allBytes[this.sizeOfFile - 1];
            int j;
            for (j = this.sizeOfFile - 1; j > 0; j--) {
                allBytes[j] = allBytes[j - 1];
            }
            allBytes[0] = tempLastByte;
        }
        for (int i = 0; i < this.sizeOfFile; i++) {

            int temp2 = (int)allBytes[i] * (-1);
            if ( (allBytes[i]>31 || allBytes[i]<0) && (temp2 >31 || temp2 < 0) &&
                                            allBytes[i] != 127 && temp2 != 127 &&
                                            allBytes[i] != 255 && temp2 != 255 ) {
                allBytes[i] = (byte)temp2;
            }
            int temp1 = (allBytes[i] ^ this.key.charAt(i % 10));
            if ( (allBytes[i]>31 || allBytes[i]<0) && (temp1 >31 || temp1 < 0) &&
                                            allBytes[i] != 127 && temp1 != 127 &&
                                            allBytes[i] != 255 && temp1 != 255 ) {
                allBytes[i] = (byte)temp1;
            }
        }

        this.outputStream = new FileOutputStream("C:/xampp/htdocs/Cipher/upload/" +this.fileName);
        outputStream.write(allBytes);
        outputStream.close();
        return 0;

    }

    /**
     * Method for showing a key.
     * @return 2 - if file has strong encryption algorithm or file is not our's
     *         1 - if email are not equal
     *         0 - if no errors were found
     */
    public int showKey() throws Exception {
        this.inputStream = new FileInputStream("C:/xampp/htdocs/Cipher/upload/" + this.fileName);
        this.allBytes = new byte[(int)(this.sizeOfFile + Math.pow(2,20))];
        inputStream.read(allBytes);
        inputStream.close();
        // checking the last byte in file
        if(allBytes[allBytes.length - 1] != '0')
            return 2;
        //checking if email in file is equal to user email
        if(checkEmails() == 1)
            return 1;

        searchAllData();
        return 0;
    }
}


