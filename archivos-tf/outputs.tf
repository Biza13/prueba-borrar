output "instance_public_ip" {
  description = "IP publica de instancia EC2"
  value = aws_instance.instancia_fedora.public_ip
}