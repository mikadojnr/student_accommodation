<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-8">
            <div class="text-center mb-8">
                <h1 class="font-heading font-bold text-3xl text-gray-900 mb-2">Biometric Verification</h1>
                <p class="text-gray-600">Take a selfie to complete your identity verification</p>
            </div>

            <!-- Instructions -->
            <div class="mb-8 p-6 bg-blue-50 rounded-lg">
                <h3 class="font-semibold text-lg mb-3">Instructions</h3>
                <ul class="space-y-2 text-sm text-gray-700">
                    <li class="flex items-start space-x-2">
                        <i class="fas fa-check text-green-600 mt-0.5"></i>
                        <span>Ensure good lighting on your face</span>
                    </li>
                    <li class="flex items-start space-x-2">
                        <i class="fas fa-check text-green-600 mt-0.5"></i>
                        <span>Look directly at the camera</span>
                    </li>
                    <li class="flex items-start space-x-2">
                        <i class="fas fa-check text-green-600 mt-0.5"></i>
                        <span>Remove glasses and hats if possible</span>
                    </li>
                    <li class="flex items-start space-x-2">
                        <i class="fas fa-check text-green-600 mt-0.5"></i>
                        <span>Keep a neutral expression</span>
                    </li>
                </ul>
            </div>

            <!-- Camera Section -->
            <div class="text-center">
                <div class="relative inline-block">
                    <video id="video" width="400" height="300" autoplay class="rounded-lg border-2 border-gray-300"></video>
                    <canvas id="canvas" width="400" height="300" class="hidden"></canvas>
                    
                    <!-- Overlay guide -->
                    <div class="absolute inset-0 flex items-center justify-center pointer-events-none">
                        <div class="w-64 h-80 border-2 border-white rounded-full opacity-50"></div>
                    </div>
                </div>
                
                <div class="mt-6 space-y-4">
                    <button id="startCamera" onclick="startCamera()" 
                            class="bg-primary text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition-colors">
                        <i class="fas fa-camera mr-2"></i>
                        Start Camera
                    </button>
                    
                    <button id="captureBtn" onclick="captureImage()" 
                            class="bg-green-600 text-white px-6 py-3 rounded-lg hover:bg-green-700 transition-colors hidden">
                        <i class="fas fa-camera mr-2"></i>
                        Capture Photo
                    </button>
                    
                    <button id="retakeBtn" onclick="retakePhoto()" 
                            class="bg-gray-600 text-white px-6 py-3 rounded-lg hover:bg-gray-700 transition-colors hidden">
                        <i class="fas fa-redo mr-2"></i>
                        Retake Photo
                    </button>
                </div>
                
                <!-- Preview Section -->
                <div id="preview" class="mt-6 hidden">
                    <h3 class="font-semibold text-lg mb-3">Photo Preview</h3>
                    <img id="capturedImage" class="mx-auto rounded-lg border-2 border-gray-300 max-w-xs">
                    
                    <form id="biometricForm" action="/verification/process-biometric" method="POST" class="mt-4">
                        <input type="hidden" id="imageData" name="image_data">
                        <button type="submit" 
                                class="bg-green-600 text-white px-8 py-3 rounded-lg hover:bg-green-700 transition-colors">
                            Submit Verification
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
let video = document.getElementById('video');
let canvas = document.getElementById('canvas');
let context = canvas.getContext('2d');
let stream = null;

async function startCamera() {
    try {
        stream = await navigator.mediaDevices.getUserMedia({ 
            video: { 
                width: 400, 
                height: 300,
                facingMode: 'user'
            } 
        });
        video.srcObject = stream;
        
        document.getElementById('startCamera').classList.add('hidden');
        document.getElementById('captureBtn').classList.remove('hidden');
    } catch (err) {
        alert('Error accessing camera: ' + err.message);
    }
}

function captureImage() {
    context.drawImage(video, 0, 0, 400, 300);
    
    // Convert to base64
    const imageData = canvas.toDataURL('image/png');
    document.getElementById('imageData').value = imageData;
    document.getElementById('capturedImage').src = imageData;
    
    // Show preview and hide video
    video.style.display = 'none';
    document.getElementById('preview').classList.remove('hidden');
    document.getElementById('captureBtn').classList.add('hidden');
    document.getElementById('retakeBtn').classList.remove('hidden');
    
    // Stop camera stream
    if (stream) {
        stream.getTracks().forEach(track => track.stop());
    }
}

function retakePhoto() {
    video.style.display = 'block';
    document.getElementById('preview').classList.add('hidden');
    document.getElementById('captureBtn').classList.remove('hidden');
    document.getElementById('retakeBtn').classList.add('hidden');
    
    startCamera();
}
</script>
