package madtodo;

import static madtodo.MadConstant._1k;
import static madtodo.MadConstant._64k;

import java.io.File;
import java.io.IOException;
import java.io.RandomAccessFile;
import java.nio.ByteBuffer;
import java.nio.channels.FileChannel;

public class MadFile {
    public static String readFileToString(String filename)
            throws IOException {
        StringBuilder sb = new StringBuilder(_64k);

        ByteBuffer bbuff = ByteBuffer.allocate(_1k);
        RandomAccessFile raf = new RandomAccessFile(new File(filename), "r");
        FileChannel fc = raf.getChannel();

        while (fc.read(bbuff) != -1) {
            bbuff.flip();
            while (bbuff.hasRemaining()) {
                sb.append((char) bbuff.get());
            }
            bbuff.clear();
        }
        raf.close();

        return sb.toString();
    }
}
